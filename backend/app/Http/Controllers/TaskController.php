<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskService $taskService)
    {
        $tasks = $taskService->getTasks($request->user);

        return $this->successResponse(message: "Data returned successfully", data: [
            "tasks" => (new TaskCollection($tasks))->toArray($request),
            'task_statuses' => $taskService->getStatuses(),
            'task_priorities' => $taskService->getPriorities()
        ]);
    }

    public function store(CreateTaskRequest $request, TaskService $taskService)
    {
        $task = $taskService->createTask(
            name: $request->name,
            user_id: auth()->user()->id,
            label: $request->label,
            priority: $request->priority,
            note: $request->note,
            status: $request->status
        );

        if ($task instanceof Task) {
            return $this->successResponse(message: "Task created successfully");
        }

        return $this->errorResponse(message: $task);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
