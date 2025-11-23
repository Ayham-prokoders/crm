<?php

namespace Tests\Unit\DealModule;

use Modules\DealManagement\Models\Invoice;
use Modules\DealManagement\Models\Deal;
use Modules\DealManagement\Models\Bank;
use App\Models\User;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex()
    {
        Invoice::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('api/invoices');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $deal = Deal::factory()->create();
        $bank = Bank::factory()->create();

        $invoiceData = [
            'deal_id' => $deal->id,
            'bank_id' => $bank->id,
            'invoice_type' => 'standard',
            'duration' => 12,
            'tax' => 10.5,
            'discount' => 5.0
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('api/invoices', $invoiceData);

        $response->assertStatus(201);
    }

    public function testShow()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson("api/invoices/{$invoice->id}");

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $invoice = Invoice::factory()->create();
        $updateData = ['tax' => 15.0];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("api/invoices/{$invoice->id}", $updateData);

        $response->assertStatus(200);

    }

    public function testDestroy()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("api/invoices/{$invoice->id}");

        $response->assertStatus(200);

    }
}
