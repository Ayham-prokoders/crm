<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition()
    {
        return [
            'course_custom_name' => $this->faker->word,
            'course_type' => $this->faker->word,
            'course_id' => Course::factory(),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'ID_certificate' => $this->faker->unique()->uuid,
            'image' => $this->faker->imageUrl,
            'pdf' => $this->faker->filePath,
            'user_id' => User::factory(),
            'origin' => 'crm',
            'show_in_website' => $this->faker->boolean,
        ];
    }
}
