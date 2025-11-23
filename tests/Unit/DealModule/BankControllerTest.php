<?php

namespace Tests\Unit\DealModule;

use Modules\DealManagement\Models\Bank;
use App\Models\User;
use Tests\TestCase;

class BankControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Bank::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/banks');

        $response->assertStatus(200);
    }

    public function testList()
    {
        Bank::factory()->count(5)->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/banks/list');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $bankData = [
            'account_holder' => 'John Doe',
            'bank_name' => 'Example Bank',
            'sort_code' => '123456',
            'swift_bic' => 'EXAMPBIC',
            'iban' => 'GB29NWBK60161331926819'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/banks', $bankData);

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $bank = Bank::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/banks/{$bank->id}");

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $bank = Bank::factory()->create();
        $updateData = [
            'bank_name' => 'Updated Bank Name',
            'account_holder'=>'account_holder'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("api/banks/{$bank->id}", $updateData);

        $response->assertStatus(200);

    }

    public function testDestroy()
    {
        $bank = Bank::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/banks/{$bank->id}");

        $response->assertStatus(200);

    }
}
