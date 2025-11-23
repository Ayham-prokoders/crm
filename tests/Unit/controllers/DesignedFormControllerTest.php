<?php

namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\DesignedForm;
use Modules\Lms\Models\Classe;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DesignedFormControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        // Create a test user
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');

    }

public function testIndex()
{
    // Create a few Notes
    DesignedForm::factory()->count(3)->create(['type'=>'general']);

    // Act as the test user
    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson('api/designed-forms');

    // Assert response
    $response->assertStatus(200);
}

public function testNotAnswerdForm()
{
    // Create a few Notes
    DesignedForm::factory()->create(['type'=>'general']);

    // Act as the test user
    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson('api/get-form-not-answered');

    // Assert response
    $response->assertStatus(200);
}

public function testAnswerdForm()
{
    // Create a few Notes
    DesignedForm::factory()->count(3)->create(['type'=>'general']);

    // Act as the test user
    $response = $this->actingAs($this->user, 'sanctum')
                     ->getJson('api/get-answered-form');

    // Assert response
    $response->assertStatus(200);
}


public function testGetFormByClassId()
{
    $class=Classe::factory()->create();
    DesignedForm::factory()->create([
        'classe_id'=>$class->id,
        'type'=>'pre_course'
    ]);

    // Act as the test user
    $response = $this->actingAs($this->user, 'sanctum')
                     ->postJson('api/get-form-class',['class_id' => $class->id]);

    // Assert response
    $response->assertStatus(200);
}

public function testStoreForm(){

    $user=User::factory()->create();
    $user->assignRole('trainee');
    $rec=[$user->email];

    $data = [
        'title' => 'New Form',
        'type' => 'general',
        'description' => 'Test description',
        'recipients'=>$rec,
        'questions' => [
            [
                'type' => 'one_choice',
                'question' => 'What is your name?',
                'is_required' => true,
                'options'=>'a or b'
            ]
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
    ->postJson('/api/designed-forms', $data);
    $response->assertStatus(201);

}

public function testUpdateForm(){

    $user=User::factory()->create();
    $user->assignRole('trainee');
    $rec=[$user->email];
    $form = DesignedForm::factory()->create(['type' => 'general']);
    $data = [
        'title' => 'Updated Form',
        'description' => 'Updated description',
        'type' => 'general',
        'recipients'=>$rec,
        'questions' => [
            [
                'type' => 'text',
                'question' => 'What is your favorite color?',
                'is_required' => false,
                'options'=>'a or b'
            ]
        ]
    ];

    $response = $this->actingAs($this->user, 'sanctum')
    ->postJson("/api/designed-forms/{$form->id}", $data);
    $response->assertStatus(200);

}

public function testDeleteForm(){

    $form = DesignedForm::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
        ->deleteJson("/api/designed-forms/{$form->id}");

        $response->assertStatus(200);
}

public function testGetSurvey()
{
    // Create a form with a slug
    $form = DesignedForm::factory()->create([
        'slug' => 'test-form',
        'type' => 'general',
    ]);

    // Act as the test user
    $response = $this
                     ->get("/survey/{$form->slug}");

    // Assert the form is returned with a 200 status
    $response->assertStatus(200);
}

public function testSubmitSurveyAuthenticatedUser()
{
    // Create a form and user
    $form = DesignedForm::factory()->create(['type' => 'general']);
    $question = $form->questions()->create([
        'type' => 'one_choice',
        'question' => 'What is your name?',
        'is_required' => true,
    ]);

    // Prepare the answers
    $data = [
        'form_id' => $form->id,
        'answers' => [
            ['question_id' => $question->id, 'answer' => 'John Doe'],
        ],
    ];

    // Act as the authenticated user
    $response = $this->actingAs($this->user, 'sanctum')
                     ->postJson('/api/submit-survey', $data);

    // Assert success response
    $response->assertStatus(200);
}

public function testSubmitSurveyGuestUser()
{
    // Create a form and question
    $form = DesignedForm::factory()->create(['type' => 'general']);
    $question = $form->questions()->create([
        'type' => 'one_choice',
        'question' => 'What is your name?',
        'is_required' => true,
    ]);

    // Prepare the data for guest submission
    $data = [
        'form_id' => $form->id,
        'email' => 'guest@example.com',
        'answers' => [
            ['question_id' => $question->id, 'answer' => 'Guest Name'],
        ],
    ];

    // Post the survey as a guest user
    $response = $this->postJson('/survey/submit', $data);

    // Assert success response
    $response->assertStatus(200);
}

public function testGetSurveyPdf()
{
    // Create a form with a slug
    $form = DesignedForm::factory()->create([
        'slug' => 'test-form',
        'type' => 'general',
    ]);

    // Act as the test user
    $response = $this->get("/survey/pdf/{$form->slug}");

    // Assert the PDF link is returned
    $response->assertStatus(200)
             ->assertJsonStructure(['link']);
}


}
