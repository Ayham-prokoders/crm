<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedule;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(1, 12) . ' weeks',
            // 'schedule' =>  Schedule::factory(),
            'days_content' => $this->faker->sentence(),
            'related_courses' => json_encode($this->faker->words(3)),
            'category_id' => $this->faker->numberBetween(1, 5),
            'online' => $this->faker->boolean(),
        ];
    }
}
