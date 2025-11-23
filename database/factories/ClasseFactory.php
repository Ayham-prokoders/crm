<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['lecture', 'workshop', 'seminar']),
            'startDate' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            // 'trainer_id' => User::factory(),
            'content_days' => $this->faker->sentence(),
            'course_id' =>Course::factory(),
            'schedule_id' => Schedule::factory(),
        ];
    }
}
