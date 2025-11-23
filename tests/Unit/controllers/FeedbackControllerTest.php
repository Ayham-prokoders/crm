<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Course;
use Modules\Lms\Models\Feedback;

class FeedbackControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Feedback::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/feedback');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $course = Course::factory()->create();
        $user = User::factory()->create();
        $data = [
            'message' => 'test',
            'rate' => 2,
            'trainee_id' => $user->id,
            'type' => 'courses',
            'course_id' => $course->id,
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/feedback', $data);
        $response->assertStatus(201);
    }



    public function testDestroy()
    {
        $item = Feedback::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/feedback/{$item->id}");
        $response->assertStatus(200);
    }
}
