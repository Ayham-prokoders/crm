<?php

namespace Modules\DealManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\DealManagement\Models\Bank;

class BankFactory extends Factory
{
    protected $model = Bank::class;

    public function definition(): array
    {
        return [
            'account_holder' => $this->faker->name,
            'bank_name' => $this->faker->company . ' Bank',
            'sort_code' => $this->faker->numerify('######'),
            'swift_bic' => $this->faker->swiftBicNumber,
            'iban' => $this->faker->iban(null),
        ];
    }
}
