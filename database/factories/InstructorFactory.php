<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'location' => $this->faker->city,
            'rating' => $this->faker->numberBetween(1, 5),
            // 'field' => $this->faker->word,
            'work_history' => $this->faker->paragraph,
            'category_id' => 1,
            'professional_summary' => json_encode($this->faker->paragraph),
            'experience' => json_encode($this->faker->paragraph),
            'qualification' => json_encode($this->faker->paragraph),
            'certification' =>json_encode($this->faker->paragraph),
            'course_experience_lpc' => json_encode($this->faker->paragraph),
            'specilized_topics' => json_encode($this->faker->paragraph),
        ];
    }
}
