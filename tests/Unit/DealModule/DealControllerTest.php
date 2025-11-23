<?php

namespace Tests\Unit\DealModule;

use Modules\DealManagement\Models\Deal;
use App\Models\User;
use Tests\TestCase;

class DealControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Deal::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/deals');

        $response->assertStatus(200);
    }

    public function testList()
    {
        Deal::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/deals/list');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $dealData = Deal::factory()->make()->toArray();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/deals', $dealData);

        $response->assertStatus(201);
    }

    public function testShow()
    {
        $deal = Deal::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/deals/{$deal->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function testUpdate()
    {
        $deal = Deal::factory()->create();
        $updateData = ['price' => 500];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("api/deals/{$deal->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);

        $this->assertDatabaseHas('deals', ['id' => $deal->id, 'price' => 500]);
    }

    public function testDestroy()
    {
        $deal = Deal::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/deals/{$deal->id}");

        $response->assertStatus(200);

    }
}
