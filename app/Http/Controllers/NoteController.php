<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

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

    // Retrieving a single note by its ID
    public function show(string $id){
        $note = Note::find($id);
        if ($note) {
            return response()->json(['note'=> $note], 200);
        }
        return response()->json(['message'=> 'Note not found !'], 404);
    }

    // Updating a note
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
    public function destroy(string $id) {
        $note = Note::find($id);
        if ($note) {
            $note->delete();
            return response()->json(['message'=> 'Note deleted successfully !']);
        }
        return response()->json(['message'=> 'Notenot found !'],404);
    }
}
