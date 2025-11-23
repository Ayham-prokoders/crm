<?php

namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Course;
use Modules\Lms\Models\Schedule;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassTestControllerTest extends TestCase
{
    // use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        Notification::fake();

        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Classe::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/classes');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $course = Course::factory()->create();
        $schedule = Schedule::factory()->create(['course_id'=>$course->id]);

        $data = [
            'title' => 'New Class',
            'description' => 'Class Description',
            'trainer_id' => $this->user->id,
            'course_id' => $course->id,
            'schedule_id' => $schedule->id,
            // 'sync_content' => true,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/classes', $data);

        $response->assertStatus(201);
    }

    // public function testShow()
    // {
    //     $classe = Classe::factory()->create();

    //     $response = $this->actingAs($this->user, 'sanctum')
    //                      ->getJson("api/classes/{$classe->id}");

    //     $response->assertStatus(200);
    // }

    public function testUpdate()
    {
        $course = Course::factory()->create();
        $schedule = Schedule::factory()->create(['course_id'=>$course->id]);
        $classe = Classe::factory()->create(['course_id'=>$course->id,'schedule_id'=>$schedule->id]);

        $data = [
            'title' => 'Updated Class',
            'description' => 'Updated description',
            'trainer_id' => $this->user->id,
            'course_id' => $course->id,
            'schedule_id' => $schedule->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/classes/{$classe->id}", $data);

        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $classe = Classe::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/classes/{$classe->id}");

        $response->assertStatus(200);
    }

    public function testGetTrainees()
    {
        $classe = Classe::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/get-trainees', ['class_id' => $classe->id]);

        $response->assertStatus(200);
    }

    public function testGetTraineeCount()
    {
        $classe = Classe::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/get-trainees-count', ['class_id' => $classe->id]);

        $response->assertStatus(200);
    }
}
