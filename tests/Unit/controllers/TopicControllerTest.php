<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\TrainerManagement\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Topic::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/topics');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $topicData = [
            'title' => 'Test Topic',
            'description' => 'This is a test topic description',
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/topics', $topicData);
        $response->assertStatus(201);
    }

    public function testShow()
    {
        $item = Topic::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/topics/{$item->id}");
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $item = Topic::factory()->create();
        $data = [
            'title' => 'Test Topic',
            'description' => 'This is a test topic description',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/topics/{$item->id}", $data);
        $response->assertStatus(200);
    }
    public function testDestroy()
    {
        $topic = Topic::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/topics/{$topic->id}");
        $response->assertStatus(200);
    }
}
