<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Classe;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'available' => $this->faker->boolean,
            'classe_id' => Classe::factory(),
            'course_id' => Course::factory(),
            'read' => $this->faker->boolean,
            'image' => $this->faker->imageUrl(),
        ];
    }
}
