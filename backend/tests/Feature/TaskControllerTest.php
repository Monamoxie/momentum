<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_list_tasks()
    {
        // Acting as a logged-in user
        $this->actingAs($this->user);

        // Create some tasks
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);

        // Send a GET request to the /tasks endpoint
        $response = $this->getJson("/api/v1/tasks");

        // Assert that the response is successful
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'tasks' => [
                        '*' => ['id', 'name', 'label', 'priority', 'status', 'note']
                    ],
                    'task_statuses',
                    'task_priorities',
                ]
            ]);
    }

    public function test_user_can_create_task()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'Test Task',
            'label' => 'Urgent',
            'priority' => 'High',
            'note' => 'This is a test task',
            'status' => 'open'
        ];

        $response = $this->postJson("/api/v1/tasks", $data);

        $response->assertStatus(200);

        // Verify the task was created
        $this->assertDatabaseHas('tasks', ['name' => 'Test Task']);
    }

    public function test_user_can_update_task()
    {
        $this->actingAs($this->user);

        // Create a task
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'name' => 'Updated Task',
            'label' => 'Work',
            'priority' => 'Medium',
            'note' => 'Updated note',
            'status' => 'in-progress'
        ];

        $response = $this->putJson("/api/v1/tasks/" .  $task->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Task updated successfully']);

        // Check if the task was updated in the database
        $this->assertDatabaseHas('tasks', ['name' => 'Updated Task']);
    }

    public function test_user_can_delete_task()
    {
        $this->actingAs($this->user);

        // Create a task
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        // Send a DELETE request to delete the task
        $response = $this->deleteJson("/api/v1/tasks/" . $task->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Task deleted successfully']);

        // Check if the task was deleted from the database
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
