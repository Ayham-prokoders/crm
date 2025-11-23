<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Attendance;
use Modules\Lms\Models\SessionCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        $class=Classe::factory()->create();
        Attendance::factory()->create(['classe_id'=>$class->id]);
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/attendance/all',['class_id'=>$class->id]);
        $response->assertStatus(200);
    }

    public function testStoreOrUdate()
    {
        $class=Classe::factory()->create();
        $session=SessionCourse::factory()->create();
        $trainee=User::factory()->create();
        $trainee->current_role_id=3;
        $trainee->save;
        $data = [
            'class_id'=>$class->id,
            'session_id'=>$session->id,
            'trainee_id'=>$trainee->id,
            'note' => 'Test Attendance',
            'status' => 'present',
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/attendance/create-or-update', $data);
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $item = Attendance::factory()->create();
        $data = [
            'note' => 'Test Attendance',
            'status' => 'present',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/attendance/update/{$item->id}", $data);
        $response->assertStatus(200);
    }
    // public function testDestroy()
    // {
    //     $item = Attendance::factory()->create();
    //     $response = $this->actingAs($this->user, 'sanctum')
    //                      ->deleteJson("api/attendances/{$item->id}");
    //     $response->assertStatus(200);
    // }
}
