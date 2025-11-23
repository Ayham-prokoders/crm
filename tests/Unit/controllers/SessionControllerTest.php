<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\SessionCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        SessionCourse::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/sessions');
        $response->assertStatus(200);
    }

    public function testList()
    {
        $class=Classe::factory()->create();
        SessionCourse::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/get-sessions-class',['class_id'=>$class->id]);
        $response->assertStatus(200);
    }
    public function testStore()
    {
        $class=Classe::factory()->create();

        $data = [
            'title' => 'Test Session',
            'description' => 'This is a test Session description',
            'duration'=>20,
            'status'=>'active',
            'class_id' => $class->id,
            'startDate'=>'2024-05-08 10:00:00'
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/sessions', $data);
        $response->assertStatus(201);
    }

    public function testGetAllSessions()
    {
        $item = SessionCourse::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/get-sessions-user");
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $item = SessionCourse::factory()->create();
        $class=Classe::factory()->create();

        $data = [
            'title' => 'Test Session',
            'description' => 'This is a test Session description',
            'duration'=>20,
            'status'=>'active',
            'class_id' => $class->id,
            'startDate'=>'2024-05-08 10:00:00'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/sessions/{$item->id}", $data);
        $response->assertStatus(200);
    }
    public function testDestroy()
    {
        $item = SessionCourse::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/sessions/{$item->id}");
        $response->assertStatus(200);
    }
}
