<?php
namespace Tests\Unit\controllers;

use Tests\TestCase;
use App\Models\User;
use Modules\Lms\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Company::factory()->count(3)->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/companies');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'name' => 'English',
            'address' => 'ghy',
        ];
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/companies', $data);
        $response->assertStatus(201);
    }

    public function testShow()
    {
        $item = Company::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/companies/{$item->id}");
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $item = Company::factory()->create();
        $data = [
            'name' => 'English',
            'address' => 'gh tres hkf',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson("api/companies/{$item->id}", $data);
        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $company = Company::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/companies/{$company->id}");
        $response->assertStatus(200);
    }
}
