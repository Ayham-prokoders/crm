<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition()
    {
        return [
            'message' => $this->faker->text,
            'rate' => $this->faker->numberBetween(1, 5),
            'trainee_id' => User::factory(),
            'type' => 'courses',
            'course_id' => Course::factory(),
            'trainer_id' => User::factory(),
        ];
    }
}
