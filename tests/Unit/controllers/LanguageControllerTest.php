<?php
namespace Tests\Unit\controllers;

use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Language::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/languages');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $languageData = [
            'name' => 'English',
            'code' => 'co-'.uniqid(),
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/languages', $languageData);
        $response->assertStatus(201);
    }

    public function testShow()
    {
        $item = Language::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/languages/{$item->id}");
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $item = Language::factory()->create();
        $data = [
            'name' => 'English',
            'code' => 'co-'.uniqid(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/languages/{$item->id}", $data);
        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $language = Language::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/languages/{$language->id}");
        $response->assertStatus(200);
    }
}
