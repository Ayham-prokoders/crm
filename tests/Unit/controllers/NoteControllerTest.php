<?php

namespace Tests\Unit\controllers;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        // Create a few Notes
        Note::factory()->count(3)->create();

        // Act as the test user
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/notes');

        // Assert response
        $response->assertStatus(200);
    }

    public function testAckNote()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create();

        $note->recipients()->attach($user->id);

        // Act as the test user
        $response =  $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/ack-note', [
        'note_id' => $note->id,
        'user_id' => $user->id,
    ]);
        // Assert response
        $response->assertStatus(200);
    }
    public function testSendedNotes()
    {
        // Create a few Notes
        Note::factory()->count(3)->create();

        // Act as the test user
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/sended-notes');

        // Assert response
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $NoteData = [
            'subject' => 'Test Note',
            'message' => 'This is a test note message',
            'level' => "normal",
            'type' => 'Note',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/notes', $NoteData);

        $response->assertStatus(201);

    }

    // public function testShow()
    // {
    //     $Note = Note::factory()->create();

    //     $response = $this->actingAs($this->user, 'sanctum')
    //                      ->json('GET', route('notes.show', $Note->id));

    //     $response->assertStatus(200);
    // }

  

    public function testDestroy()
    {
        $Note = Note::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/notes/$Note->id");

        $response->assertStatus(200);

    }
}
