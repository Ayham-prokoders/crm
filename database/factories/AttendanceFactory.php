<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\SessionCourse;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['present', 'absent', 'tardiness']),
            'note' => $this->faker->sentence(),
            // 'lang_code' => $this->faker->randomElement(['en', 'ar']),
            'classe_id' => Classe::factory(),
            'session_id' => SessionCourse::factory(),
            'trainee_id' => User::factory(),
        ];
    }
}
