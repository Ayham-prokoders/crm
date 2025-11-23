<?php

namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\UploadedFile;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionControllerTest extends TestCase
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
        Question::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/questions');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'type' => 'one_choice',
            'question' => 'What is your name?',
            'is_required' => true,
            'options'=>'a or b'
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/questions', $data);
        $response->assertStatus(201);
    }
    public function testUpdate()
    {
        $question=Question::factory()->create();
        $data = [
            'type' => 'one_choice',
            'question' => 'What is your name?',
            'is_required' => true,
            'options'=>'a or b'
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/questions/'.$question->id, $data);
        $response->assertStatus(200);
    }
    public function testDelete()
    {
        $question=Question::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson('api/questions/'.$question->id);
        $response->assertStatus(200);
    }
   }
