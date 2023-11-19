<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Note;
use Illuminate\Support\Facades\Hash;

class NotesManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_notes_for_authenticated_user()
    {
        // Assuming you have a user authenticated
        $user = User::factory()->create([
            'password' => Hash::make('Password11**'),
        ]);
        $this->actingAs($user);

        // Assuming the user has notes
        $user = Note::factory()->create([
            'title' => 'Test Note Title',
            'content' => 'Test Note Content',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/api/notes');

        $response->assertStatus(200)
            ->assertJsonStructure(['note'])
            ->assertJsonCount(3, 'note');
    }

    public function test_it_returns_message_for_user_with_no_notes()
    {
        // Assuming you have a user authenticated
        $user = User::factory()->create([
            'password' => Hash::make('Password11**'),
        ]);
        $this->actingAs($user);

        $response = $this->get('/api/notes');

        $response->assertStatus(200)
            ->assertJson(['message' => 'No notes created !']);
    }

    public function test_it_creates_a_note()
    {
        // Assuming you have a user authenticated
        $user = User::factory()->create([
            'password' => Hash::make('Password11**'),
        ]);
        $this->actingAs($user);

        $data = [
            'title' => 'Test Note Title',
            'content' => 'Test Note Content',
            'user_id' => $user->id,
        ];

        $response = $this->post('/api/notes/create', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Note created successfully!'])
            ->assertJsonStructure(['note']);
    }
}
