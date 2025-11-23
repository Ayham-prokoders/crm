<?php

namespace Modules\TaskManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TaskManagement\Models\Task;
use Modules\DealManagement\Models\Deal;
use App\Models\User;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'task_code' => strtoupper(Str::random(12)),
            'deal_id' => Deal::factory(),
            'assign_to' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'inprogress', 'done']),
            'description' => $this->faker->sentence(),
            'title' => $this->faker->sentence(3),
            'duration' => $this->faker->randomDigitNotNull() . ' hours',
            'hotel_booking' => $this->faker->boolean(),
            'hotel_name' => $this->faker->company(),
            'hotel_fees' => $this->faker->randomFloat(2, 100, 1000),
            'hotel_paid' => $this->faker->boolean(),
            'taxi_booking1' => $this->faker->boolean(),
            'taxi_booking2' => $this->faker->boolean(),
            'pre_questioner' => $this->faker->boolean(),
        ];
    }
}
