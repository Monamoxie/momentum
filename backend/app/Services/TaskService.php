<?php

namespace App\Services;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TaskService
{
    public function getTasks(?User $user, ?int $paginate = null): LengthAwarePaginator
    {
        $tasks = Task::query()->whereNull('parent_id')->with('children');
        if ($user instanceof User) {
            $tasks = $tasks->where('user_id', $user->id);
        }

        $paginationLimit = !is_null($paginate) ? $paginate : 200;

        return $tasks->paginate($paginationLimit);
    }

    public function createTask(...$payload): Task|string
    {
        try {
            $task = new Task;
            $task->name = $payload['name'];
            $task->user_id = $payload['user_id'];
            $task->label = $payload['label'];
            $task->priority = $payload['priority'];
            $task->note = $payload['note'];
            $task->status = $payload['status'];

            $task->save();

            return $task;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function updateTask(...$payload): Task|string
    {
        try {
            $task = Task::find($payload['id']);

            $task->name = $payload['name'];
            $task->user_id = $payload['user_id'];
            $task->label = $payload['label'];
            $task->priority = $payload['priority'];
            $task->note = $payload['note'];
            $task->status = $payload['status'];

            $task->save();

            return $task;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getStatuses(): array
    {
        $statuses = [];
        foreach (TaskStatusEnum::cases() as $case) {
            $statuses[] = $case->value;
        }

        return $statuses;
    }

    public function getPriorities(): array
    {
        $priorities = [];
        foreach (TaskPriorityEnum::cases() as $case) {
            $priorities[] = $case->value;
        }

        return $priorities;
    }
}
