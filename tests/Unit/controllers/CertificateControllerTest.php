<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Course;
use Modules\Lms\Models\Certificate;
use Illuminate\Support\Facades\Notification;

class CertificateControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        Notification::fake();
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Certificate::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/certificates');
        $response->assertStatus(200);
    }



    public function testShow()
    {
        $item = Certificate::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/certificates/{$item->id}");
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $course = Course::factory()->create();
        $class= Classe::factory()->create(['course_id'=>$course->id]);
        $user = User::factory()->create();
        $class->trainees()->attach($user);
        $data = [
        'class_id'=>$class->id,
        'ID_certificate' => 'CERT123456'.uniqid(),
        'image' => 'path/to/image.jpg',
        'pdf' => 'path/to/certificate.pdf',
        'user_id' => $user->id,
        'origin' => 'crm',
        'show_in_website' => true,
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/certificates', $data);
        $response->assertStatus(201);
    }
    public function testUpdate()
    {
        $item = Certificate::factory()->create();
        $course = Course::factory()->create();
        $class= Classe::factory()->create(['course_id'=>$course->id]);
        $user = User::factory()->create();
        $class->trainees()->attach($user);
        $data = [
        'class_id'=>$class->id,
        'ID_certificate' => 'CERT123456'.uniqid(),
        'image' => 'path/to/image.jpg',
        'pdf' => 'path/to/certificate.pdf',
        'user_id' => $user->id,
        'origin' => 'crm',
        'show_in_website' => true,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/certificates/{$item->id}", $data);
        $response->assertStatus(200);
    }
    public function testDestroy()
    {
        $certificate = Certificate::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/certificates/{$certificate->id}");
        $response->assertStatus(200);
    }
}
