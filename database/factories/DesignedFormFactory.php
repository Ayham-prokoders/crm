<?php

namespace Database\Factories;

use App\Models\DesignedForm;
use App\Models\Classe;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

class DesignedFormFactory extends Factory
{
    protected $model = DesignedForm::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            // 'type' => $this->faker->randomElement(['pre_course', 'by_role', 'general']),
            'type'=>'general',
            'description' => $this->faker->paragraph(),
            'classe_id' => Classe::factory(),
        ];
    }

}
