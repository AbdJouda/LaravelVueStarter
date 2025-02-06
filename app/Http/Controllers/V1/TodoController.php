<?php

namespace App\Http\Controllers\V1;

use App\Facades\BossResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TodoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Retrieve Tasks.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTodos(Request $request): JsonResponse
    {
        $todos = $this->user->todos()
            ->search($request->query('search'))
            ->when($request->query('priority'), function ($q, $priority) {
                return $q->where('priority', $priority);
            })
            ->when($request->has('is_completed'), function ($q) use ($request) {
                return $q->where('is_completed', $request->query('is_completed'));
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->get();

        return BossResponse::withData($todos)
            ->asSuccess();
    }

    /**
     * Create a new task
     *
     * @param TodoRequest $request
     * @return JsonResponse
     */
    public function createTodo(TodoRequest $request): JsonResponse
    {
        $todo = $this->user->todos()
            ->create($request->validated() + ['user_id' => $this->user->getKey()]);

        return BossResponse::withMessage(__('actions.success.save', ['name' => __('Task'),]))
            ->withData($todo)
            ->asSuccess();
    }

    /**
     * Update existing task
     *
     * @param TodoRequest $request
     * @param string|null $todoId
     * @return JsonResponse
     */
    public function updateTodo(TodoRequest $request, ?string $todoId): JsonResponse
    {
        $todo = $this->user->todos()
            ->findOrFail($todoId);

        $todo->update($request->validated());


        return BossResponse::withMessage(__('actions.success.update', ['name' => __('Task'),]))
            ->withData($todo)
            ->asSuccess();
    }

    /**
     * Toggle task complete status
     *
     * @param string $todoId
     * @return JsonResponse
     */
    public function changeTaskCompletionStatus(string $todoId): JsonResponse
    {
        $todo = $this->user
            ->todos()
            ->findOrFail($todoId);

        $todo->forceFill(['is_completed' => !$todo->is_completed])->save();

        return BossResponse::withMessage( __('actions.success.update', ['name' =>  __('Task')]))
            ->asSuccess();
    }

    /**
     * Delete task.
     *
     * @param Request $request
     * @param string $todoId
     * @return JsonResponse
     */
    public function deleteTodo(Request $request, string $todoId): JsonResponse
    {

        $this->user->todos()
            ->findOrFail($todoId)
            ->delete();

        return BossResponse::withMessage(__('actions.success.delete', ['name' => __('Task')]))
            ->asSuccess();
    }
}
