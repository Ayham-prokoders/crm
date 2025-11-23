<?php

namespace Database\Factories;

use App\Models\GuestSurvey;
use App\Models\DesignedForm;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestSurveyFactory extends Factory
{
    protected $model = GuestSurvey::class;

    public function definition()
    {
        return [
            'designed_form_id' => DesignedForm::factory(),
            'email' => $this->faker->email(),
            'info' => $this->faker->text(),
        ];
    }
}
