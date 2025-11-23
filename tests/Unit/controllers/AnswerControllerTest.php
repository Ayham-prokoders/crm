<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use App\Models\DesignedForm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Answer::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/answers');
        $response->assertStatus(200);
    }
//
    public function testStore()
    {
        $question = Question::factory()->create();
        $form = DesignedForm::factory()->create();
        $form->recipients()->attach($this->user->id);

        $data = [
            'form_id' => $form->id,
            'answers' => [
                [
                    'question_id' => $question->id,
                    'answer' => 'test'
                ]
            ]
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/answers', $data);
        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $item = Answer::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/answers/{$item->id}");
        $response->assertStatus(200);
    }
}
