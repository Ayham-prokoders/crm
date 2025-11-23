<?php

namespace Modules\DealManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\DealManagement\Models\Invoice;
use Modules\DealManagement\Models\Deal;
use Modules\DealManagement\Models\Bank;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'deal_id' => Deal::factory(),
            'bank_id' => Bank::factory(),
            'invoice_type' => $this->faker->randomElement(['standard', 'without_discount']),
            'duration' => $this->faker->numberBetween(1, 12),
            'tax' => $this->faker->randomFloat(2, 0, 20),
            'discount' => $this->faker->randomFloat(2, 0, 50),
        ];
    }
}
