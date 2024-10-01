<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $taskService = new TaskService;
        $priorities = $taskService->getPriorities();
        $statuses = $taskService->getStatuses();

        return [
            'name' => $this->faker->sentence(3),
            'user_id' => User::factory(),
            'label' => $this->faker->sentence(1),
            'priority' => $this->faker->randomElement($priorities),
            'note' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
