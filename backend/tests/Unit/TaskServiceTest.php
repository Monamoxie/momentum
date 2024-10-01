<?php

namespace Tests\Unit;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

final class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $taskService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = app(TaskService::class);
        $this->user = User::factory()->create();
    }

    public function test_create_task_successfully()
    {
        $taskData = [
            'name' => 'New Task',
            'user_id' => $this->user->id,
            'label' => 'Work',
            'priority' => 'High',
            'note' => 'This is a new task',
            'status' => 'open'
        ];

        $task = $this->taskService->createTask(...$taskData);


        $this->assertInstanceOf(Task::class, $task);


        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task',
            'user_id' => $this->user->id
        ]);
    }

    public function test_update_task_successfully()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $name = fake()->sentence(4);

        $updatedData = [
            'id' => $task->id,
            'name' => $name,
            'user_id' => $this->user->id,
            'label' => fake()->sentence(1),
            'priority' => TaskPriorityEnum::LOW->value,
            'note' => fake()->sentence(),
            'status' => TaskStatusEnum::PENDING->value,
        ];

        $updatedTask = $this->taskService->updateTask(...$updatedData);

        $this->assertInstanceOf(Task::class, $updatedTask);

      
        $this->assertDatabaseHas('tasks', ['name' => $name]);
    }

    public function test_get_tasks_for_user()
    {
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);

        $tasks = $this->taskService->getTasks($this->user);

        $this->assertCount(5, $tasks->items());
    }

    public function test_get_priorities()
    {
        $priorities = $this->taskService->getPriorities();


        $this->assertIsArray($priorities);
        $this->assertContains(TaskPriorityEnum::HIGH->value, $priorities);
        $this->assertContains(TaskPriorityEnum::MEDIUM->value, $priorities);
        $this->assertContains(TaskPriorityEnum::LOW->value, $priorities);
    }

    public function test_get_statuses()
    {
        $statuses = $this->taskService->getStatuses();


        $this->assertIsArray($statuses);
        $this->assertContains(TaskStatusEnum::PENDING->value, $statuses);
        $this->assertContains(TaskStatusEnum::ONGOING->value, $statuses);
        $this->assertContains(TaskStatusEnum::COMPLETED->value, $statuses);
    }
}
