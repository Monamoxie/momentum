<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{

    public function index(Request $request, TaskService $taskService)
    {
        $tasks = $taskService->getTasks(auth()->user());

        return $this->successResponse(message: "Data returned successfully", data: [
            "tasks" => (new TaskCollection($tasks))->toArray($request),
            'task_statuses' => $taskService->getStatuses(),
            'task_priorities' => $taskService->getPriorities()
        ]);
    }

    public function show(Request $request, Task $task, TaskService $taskService)
    {
        return $this->successResponse(message: "Task retrieved successfully", data: [
            'task' => new TaskResource($task)
        ]);
    }

    public function store(CreateTaskRequest $request, TaskService $taskService)
    {
        $task = $taskService->createTask(
            parent_id: $request->parent_id,
            name: $request->name,
            user_id: auth()->user()->id,
            label: $request->label,
            priority: $request->priority,
            note: $request->note,
            status: $request->status
        );

        if ($task instanceof Task) {
            return $this->successResponse(message: "Task created successfully", data: [
                'task' => new TaskResource($task)
            ]);
        }

        return $this->errorResponse(message: $task);
    }

    public function update(UpdateTaskRequest $request, Task $task, TaskService $taskService)
    {
        $task = $taskService->updateTask(
            id: $task->id,
            name: $request->name,
            user_id: auth()->user()->id,
            label: $request->label,
            priority: $request->priority,
            note: $request->note,
            status: $request->status
        );

        if ($task instanceof Task) {
            return $this->successResponse(message: "Task updated successfully", data: [
                'task' => new TaskResource($task)
            ]);
        }

        return $this->errorResponse(message: $task);
    }

    public function destroy(Request $request, Task $task)
    {
        $task->delete();
        return $this->successResponse(message: "Task deleted successfully");
    }
}
