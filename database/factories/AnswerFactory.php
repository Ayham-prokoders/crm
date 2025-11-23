<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\GuestSurvey;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition()
    {
        return [
            'answer' => json_encode($this->faker->text()),
            'question_id' => Question::factory(),
            'user_id' => User::factory(),
            // 'guest_survey_id' => GuestSurvey::factory(),
        ];
    }

}
