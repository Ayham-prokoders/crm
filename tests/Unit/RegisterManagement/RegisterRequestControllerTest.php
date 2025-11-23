<?php
namespace Tests\Unit\RegisterManagement;

use Tests\TestCase;
use Modules\RegisterManagement\Models\RegisterRequest;
use Modules\RegisterManagement\Http\Requests\ApproveRegisterRequest;
use App\Http\Helper\RegisterationRequestHelper;
use Modules\RegisterManagement\Http\Services\ApproveRegisterationService;
use App\Models\User;

class RegisterRequestControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        RegisterRequest::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/register-request/all');
        $response->assertStatus(200);
    }

    public function testSyncRequests()
    {
        // $mockHelper = \Mockery::mock(RegisterationRequestHelper::class);
        // $mockHelper->shouldReceive('syncRegisterRequests')->andReturn(5);
        // $this->app->instance(RegisterationRequestHelper::class, $mockHelper);

        // $response = $this->actingAs($this->user, 'sanctum')
        //                  ->postJson('api/register-request/sync-from-website');
        // $response->assertStatus(200)
        //          ->assertJson(['success' => true, 'message' => 'Registration requests synchronized successfully. Total: 5']);
    }

    public function testApprove()
    {
        // $request = RegisterRequest::factory()->create(['status' => 'waiting']);

        // $mockService = \Mockery::mock(ApproveRegisterationService::class);
        // $mockService->shouldReceive('approve')->once();
        // $this->app->instance(ApproveRegisterationService::class, $mockService);

        // $response = $this->actingAs($this->user, 'sanctum')
        //                  ->postJson("api/register-request/approve/{$request->id}");
        // $response->assertStatus(200);
    }

    public function testCancel()
    {
        $item = RegisterRequest::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/register-request/cancel/{$item->id}");
        $response->assertStatus(200);
    }
}
