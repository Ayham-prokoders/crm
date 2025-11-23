<?php

namespace Modules\DealManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\DealManagement\Models\Deal;
use App\Models\Course;
use App\Models\Classe;
use App\Models\Company;
use App\Models\User;

class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'classe_id' => Classe::factory(),
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'type' => $this->faker->randomElement(['individual', 'company']),
            'currency' => $this->faker->currencyCode(),
            'language' => $this->faker->randomElement(['en', 'fr', 'ar']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'paypal']),
            'payment_mode' => $this->faker->randomElement(['invoice_me', 'invoice_company'])
        ];
    }
}
