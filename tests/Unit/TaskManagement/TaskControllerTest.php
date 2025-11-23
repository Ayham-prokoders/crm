<?php

namespace Tests\Unit\TaskManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TaskManagement\Models\Task;
use Modules\DealManagement\Models\Deal;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testStore()
    {
        $deal = Deal::find(1);
        $trainee = User::factory()->create();

        $data = [
            'deal_id' => $deal->id,
            'assign_to' => $this->user->id,
            'status' => 'pending',
            'description' => 'Test task description',
            'duration' => '3 hours',
            'hotel_booking' => true,
            'hotel_name' => 'Test Hotel',
            'hotel_fees' => '500',
            'hotel_paid' => true,
            'taxi_booking1' => true,
            'taxi_booking2' => false,
            'pre_questioner' => true,
            'trainees' => [$trainee->id],
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/tasks', $data);

        $response->assertStatus(201);
    }

    public function testIndex()
    {
        Task::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/tasks');

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/tasks/{$task->id}");

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $task = Task::factory()->create();
        $newData = ['status' => 'completed'];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("api/tasks/{$task->id}", $newData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function testDestroy()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/tasks/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
