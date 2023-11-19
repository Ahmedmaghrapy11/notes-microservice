<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Services\RabbitMQService;

/**
 * @OA\Schema(
 *     schema="Note",
 *     required={"title", "content"},
 *     @OA\Property(property="title", type="string", example="Note Title"),
 *     @OA\Property(property="content", type="string", example="Note Content"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 * )
 */

class NoteController extends Controller
{

    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function listenForAuthenticationEvents()
    {
        $this->rabbitMQService->connect();
        $channel = $this->rabbitMQService->channel();
        $channel->exchangeDeclare('authentication_exchange', 'direct', false, true, false);
        $channel->queueDeclare('authentication_queue', false, true, false, false);
        $channel->queueBind('authentication_queue', 'authentication_exchange', 'authentication_queue');

        $channel->consume(function ($message, $channel, $client) {
            // Handle authentication event
            echo $message->content . "\n";
            $channel->ack($message);
        });

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $this->rabbitMQService->disconnect();
    }

/**
 * @OA\Get(
 *     path="/api/notes",
 *     summary="Get a list of notes",
 *     tags={"Notes"},
 *     @OA\Response(response=200, description="Successful operation", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Note"))),
 * )
 */
    // Retrieving a list of all notes for a user
    public function index() {
        $user = auth()->user();
        $notes = $user->notes;
        if ($notes->count() > 0) {
            return response()->json(['note' => $notes],200);
        }
        return response()->json(['message' => 'No notes created !'],200);
    }

    // Creating a new note
/**
 * @OA\Post(
 *     path="/api/notes",
 *     summary="Create a new note",
 *     tags={"Notes"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Note data",
 *         @OA\JsonContent(ref="#/components/schemas/Note"),
 *     ),
 *     @OA\Response(response=201, description="Note created successfully", @OA\JsonContent(ref="#/components/schemas/Note")),
 * )
 */
    public function create(Request $request) {
        $noteValidatedData = $request->validate([
            'title' => 'string|required|min:12',
            'content' => 'string|required',
            'user_id' => 'required'
        ]);
        dd($noteValidatedData);
        $note = Note::create($noteValidatedData);
        return response()->json(['message' => 'Note created successfully!', 'note' => $note],201);
    }

    /**
 * @OA\Get(
 *     path="/api/notes/{id}",
 *     summary="Get a specific note",
 *     tags={"Notes"},
 *     @OA\Parameter(name="id", in="path", required=true, description="ID of the note", @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/Note")),
 *     @OA\Response(response=404, description="Note not found"),
 * )
 */
    // Retrieving a single note by its ID
    public function show(string $id){
        $note = Note::find($id);
        if ($note) {
            return response()->json(['note'=> $note], 200);
        }
        return response()->json(['message'=> 'Note not found !'], 404);
    }

    // Updating a note
    /**
 * @OA\Put(
 *     path="/api/notes/{id}",
 *     summary="Update a specific note",
 *     tags={"Notes"},
 *     @OA\Parameter(name="id", in="path", required=true, description="ID of the note", @OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Updated note data",
 *         @OA\JsonContent(ref="#/components/schemas/Note"),
 *     ),
 *     @OA\Response(response=200, description="Note updated successfully", @OA\JsonContent(ref="#/components/schemas/Note")),
 *     @OA\Response(response=404, description="Note not found"),
 * )
 */
    public function update(Request $request, string $id) {
        $note = Note::find($id);
        $noteValidatedData = $request->validate([
            'title' => 'string|required|min:12',
            'content' => 'string|required',
            'user_id' => 'required'
        ]);
        $note->update($noteValidatedData);
        return response()->json(['message'=> 'Note updated successfully !', 'note' => $note],200);
    }

    // Deleting a note
    /**
 * @OA\Delete(
 *     path="/api/notes/{id}",
 *     summary="Delete a specific note",
 *     tags={"Notes"},
 *     @OA\Parameter(name="id", in="path", required=true, description="ID of the note", @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Note deleted successfully"),
 *     @OA\Response(response=404, description="Note not found"),
 * )
 */
    public function destroy(string $id) {
        $note = Note::find($id);
        if ($note) {
            $note->delete();
            return response()->json(['message'=> 'Note deleted successfully !']);
        }
        return response()->json(['message'=> 'Notenot found !'],404);
    }
}
