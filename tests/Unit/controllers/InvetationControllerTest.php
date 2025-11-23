<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Invitation;
use Modules\Lms\Models\Classe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvetationControllerTest extends TestCase
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
        Invitation::factory()->create(['classe_id'=>$class->id]);
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/invitation');
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $class=Classe::factory()->create();
        $trainee=User::factory()->create();
        $emls=[$trainee->email];
        $data = [
            'emails'=>$emls,
            'class_id'=>$class->id
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/invitation', $data);
        $response->assertStatus(200);
    }
}
