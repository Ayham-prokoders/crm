<?php
namespace Tests\Unit\controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testListBlog()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/posts');
        $response->assertStatus(200);
    }
    public function testListEvent()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/events');
        $response->assertStatus(200);
    }

    public function testListCities()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/cities');
        $response->assertStatus(200);
    }

    public function testAudit()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/audit-logs');
        $response->assertStatus(200);
    }

    public function testNotification()
    {
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/notifications');
        $response->assertStatus(200);
    }
}
