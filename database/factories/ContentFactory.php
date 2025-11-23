<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'file' => $this->faker->filePath(),
            'class_id' => Classe::factory(),
        ];
    }
}
