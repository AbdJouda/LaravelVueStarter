<?php

use App\Events\V1\RolesUpdatedEvent;
use App\Http\Controllers\V1\Admin\SettingsController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\ResetPasswordController;
use App\Http\Controllers\V1\ProfileController;
use App\Http\Controllers\V1\TodoController;
use App\Models\User;
use App\Notifications\V1\UserRoleUpdateNotification;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function ($route) {
    $route->post('login', [LoginController::class, 'login']);
    $route->get('logout', [LoginController::class, 'logout'])
        ->middleware('auth:sanctum');
    $route->post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
    $route->post('verify-reset-code', [ResetPasswordController::class, 'verifyResetCode']);
    $route->post('reset-password', [ResetPasswordController::class, 'resetPassword']);

});

Route::prefix('profile')->middleware(['auth:sanctum'])->group(function ($route) {
    $route->get('', [ProfileController::class, 'getProfile']);
    $route->post('update', [ProfileController::class, 'updateProfile']);
    $route->post('update-password', [ProfileController::class, 'updatePassword']);
    $route->post('request-account-delete', [ProfileController::class, 'requestDeletion']);
    $route->get('roles', [ProfileController::class, 'getRoles']);
    $route->get('notifications', [ProfileController::class, 'getNotifications']);

});

Route::prefix('settings')->group(function ($route) {
    $route->get('', [SettingsController::class, 'getSettings']);
});

Route::prefix('todos')->middleware(['auth:sanctum'])->group(function ($route) {
    $route->get('', [TodoController::class, 'getTodos']);
    $route->get('/upcoming', [TodoController::class, 'getUpcomingTodos']);
    $route->post('/create', [TodoController::class, 'createTodo']);
    $route->patch('{todoId}/update', [TodoController::class, 'updateTodo']);
    $route->delete('{todoId}/delete', [TodoController::class, 'deleteTodo']);
    $route->patch('{todoId}/toggle-complete-status', [TodoController::class, 'changeTaskCompletionStatus']);
});

Route::get('test', function () {
    $user = User::findOrFail('9de8f909-1aef-404f-81bc-5f50e0a15d82');
    $user->notify(new UserRoleUpdateNotification());
});
