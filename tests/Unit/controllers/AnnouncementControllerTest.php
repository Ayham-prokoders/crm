<?php

namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Announcement;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Course;
use Illuminate\Http\UploadedFile;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnouncementControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    public function testSendCourseAnnouncements()
    {

        $course = Course::factory()->create();
        $class = Classe::factory()->create();
        $instructors = User::factory()->count(2)->create();

        // Prepare request data
        $data = [
            'course_id' => $course->id,
            'class_id' => $class->id,
            'instructor_ids' => $instructors->pluck('id')->toArray(),
            'image' => 'sample-image.jpg'
        ];

        // Act as the admin and send the POST request
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/send-course-announcement', $data);

        // Assert response and database
        $response->assertStatus(200);

    }

    public function testSetAvailabilityByInstructor()
    {
        // Create a trainer user and authenticate
        $trainer = User::factory()->create();
        $trainerRole = Role::where('name', 'trainer')->first();
        $trainer->assignRole($trainerRole);

        $course = Course::factory()->create();
        $class = Classe::factory()->create();

        // Create an announcement
        $announcement = Announcement::factory()->create([
            'user_id' => $trainer->id,
            'course_id' => $course->id,
            'classe_id' => $class->id,
            'available' => false
        ]);

        // Prepare request data
        $data = [
            'instructor_id' => $trainer->id,
            'course_id' => $course->id,
            'class_id' => $class->id,
            'status' => true
        ];

        // Act as the trainer and send the POST request
        $response = $this->actingAs($trainer, 'sanctum')
                        ->postJson('/api/instructor/availability', $data);

        // Assert response and database
        $response->assertStatus(200);

    }

    public function testGetAvailableInstructors()
    {

        $class = Classe::factory()->create();
        $instructors = User::factory()->count(2)->create();

        // Set instructors to be available for the class
        foreach ($instructors as $instructor) {
            Announcement::factory()->create([
                'user_id' => $instructor->id,
                'classe_id' => $class->id,
                'available' => true
            ]);
        }

        // Act as the admin and send the POST request
        $response = $this->actingAs($this->user, 'sanctum')
                        ->postJson('/api/get-available-instructor', ['class_id' => $class->id]);

        // Assert response and data
        $response->assertStatus(200);
    }

    public function testGetCourseAnnouncement()
    {

        $course = Course::factory()->create();
        $class = Classe::factory()->create();
        $announcements = Announcement::factory()->count(3)->create([
            'course_id' => $course->id,
            'classe_id' => $class->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                        ->getJson('/api/');

        // Assert response and data
        $response->assertStatus(200);
    }

    public function testDestroyAnnouncement()
    {
        // Create an announcement
        $announcement = Announcement::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                        ->deleteJson("/api/announcement/{$announcement->id}");

        // Assert response
        $response->assertStatus(200);
    }

}
