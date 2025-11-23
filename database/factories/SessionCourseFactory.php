<?php

namespace Database\Factories;

use App\Models\SessionCourse;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionCourseFactory extends Factory
{
    protected $model = SessionCourse::class;

    public function definition()
    {
        return [
            // 'lang_code' => $this->faker->randomElement(['en', 'ar']),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'canceled']),
            'duration' => $this->faker->numberBetween(30, 180),
            'startDate' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'classe_id' => Classe::factory(),
        ];
    }
}
