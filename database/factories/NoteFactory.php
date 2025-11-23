<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\NoteLevelEnum;
use App\Enums\NoteTypeEnum;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'level' => $this->faker->randomElement(NoteLevelEnum::getValues()),
            'type' => $this->faker->randomElement(NoteTypeEnum::getValues()),
            'role_ids' => json_encode([$this->faker->numberBetween(1, 5)]),
        ];
    }
}
