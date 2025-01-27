<?php

use App\Http\Controllers\V1\Admin\RolePermissionController;
use App\Http\Controllers\V1\Admin\SettingsController;
use App\Http\Controllers\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function ($route) {
    $route->get('/', [UserController::class, 'getUsers'])
        ->middleware('permission:view_users');
    $route->patch('{user}/toggle-status', [UserController::class, 'changeUserStatus'])
        ->middleware('permission:edit_users');
    $route->get('{user}/details', [UserController::class, 'geUserDetails'])
        ->middleware('permission:edit_users');
    $route->post('/create', [UserController::class, 'createUpdateUser'])
        ->middleware('permission:create_users');
    $route->patch('{user}/update', [UserController::class, 'createUpdateUser'])
        ->middleware('permission:update_users');
    $route->get('{user}/reset-password', [UserController::class, 'resetUserPassword'])
        ->middleware('permission:edit_users');
});
Route::prefix('roles')->group(function ($route) {
    $route->get('/', [RolePermissionController::class, 'getRoles'])
        ->middleware('permission:view_roles|edit_users');
    $route->get('{role}/details', [RolePermissionController::class, 'getRoleDetails'])
        ->middleware('permission:edit_roles');
    $route->post('/create', [RolePermissionController::class, 'createUpdateRole'])
        ->middleware('permission:create_roles');
    $route->patch('{role}/update', [RolePermissionController::class, 'createUpdateRole'])
        ->middleware('permission:update_roles');
    $route->get('/permissions', [RolePermissionController::class, 'getPermissions'])
        ->middleware('permission:create_roles|update_roles|edit_roles');
    $route->get('/unassigned-permissions', [RolePermissionController::class, 'getUnassignedPermissions'])
        ->middleware('permission:create_roles|update_roles|edit_roles|edit_users');
    $route->delete('{role}/delete', [RolePermissionController::class, 'deleteRole'])
        ->middleware('permission:delete_roles');
});

Route::prefix('settings')->group(function ($route) {
    $route->post('', [SettingsController::class, 'updateSettings'])
        ->middleware('permission:update_system_settings');

});
