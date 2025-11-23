<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition()
    {
        // Create and retrieve the User and Course models first
        $user = User::factory()->create();
        $course = Course::factory()->create();

        return [
            'start_date' => $this->faker->date(),
            'city_id' => 30,
            'online' => $this->faker->boolean(),
            // 'trainer_id' => $user->id,
            'course_id' => $course->id,
        ];
    }
}
