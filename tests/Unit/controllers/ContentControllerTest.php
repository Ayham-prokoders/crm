<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Content::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/content');
        $response->assertStatus(200);
    }

    public function testList()
    {
        $class=Classe::factory()->create();
        Content::factory()->create(['class_id'=>$class->id]);
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/get-content-class',['class_id'=>$class->id]);
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $class=Classe::factory()->create();
        $data = [
            // 'name' => 'Test Content',
            'files'=>[['name'=>'test','path'=>'url'],['name'=>'test2','path'=>'url2']],
            'class_id'=>$class->id
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/content', $data);
        $response->assertStatus(201);
    }

    public function testShow()
    {
        $item = Content::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/content/{$item->id}");
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $item = Content::factory()->create();
        $class=Classe::factory()->create();
        $data = [
            'files'=>[['name'=>'test','path'=>'url'],['name'=>'test2','path'=>'url2']],
            'class_id'=>$class->id
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/content/{$item->id}", $data);
        $response->assertStatus(200);
    }
    public function testDestroy()
    {
        $item = Content::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/content/{$item->id}");
        $response->assertStatus(200);
    }
}
