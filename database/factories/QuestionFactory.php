<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\DesignedForm;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['one_choice','text','multi_choice']),
            'question' => $this->faker->sentence(),
            'options' => json_encode($this->faker->words(4)),
            'designed_form_id' => DesignedForm::factory(),
            'is_required' => $this->faker->boolean(),
        ];
    }
}
