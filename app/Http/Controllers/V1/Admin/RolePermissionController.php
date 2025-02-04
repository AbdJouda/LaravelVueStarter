<?php

namespace App\Http\Controllers\V1\Admin;

use App\Facades\BossResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RoleRequest;
use App\Jobs\RemoveUsersPermissionsJob;
use App\Jobs\UpdateUsersPermissionsJob;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Retrieve a list of roles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoles(Request $request): JsonResponse
    {

        $roles = Role::query()
            ->search($request->query('search'))
            ->with('permissions')
            ->where('name', '!=', 'admin')
            ->withCount('users')
            ->withCount('permissions')
            ->latest()
            ->get();

        return BossResponse::withData($roles)
            ->asSuccess();
    }

    /**
     * Retrieve a given role details
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function getRoleDetails(Role $role): JsonResponse
    {

        return BossResponse::withData($role->load('permissions'))
            ->asSuccess();
    }

    /**
     * Retrieve a list of permissions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPermissions(Request $request): JsonResponse
    {
        $permissions = Permission::query()
            ->get();

        return BossResponse::withData($permissions)
            ->asSuccess();
    }

    /**
     * Retrieve a list of permissions that are not assigned to roles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUnassignedPermissions(Request $request): JsonResponse
    {
        $permissions = Permission::query()
            ->whereDoesntHave('roles')
            ->get();

        return BossResponse::withData($permissions)
            ->asSuccess();
    }


    /**
     * Create or Update existing role
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function createUpdateRole(RoleRequest $request, Role $role): JsonResponse
    {

        $isNewRole = !$role->exists;

        $role->fill($request->only('name'))->save();

        $permissions = $request->input('permissions');

        $isNewRole
            ? $role->givePermissionTo($permissions)
            : $role->syncPermissions($permissions);


        if (!$isNewRole && $request->boolean('apply_to_users')) {
            UpdateUsersPermissionsJob::dispatch($role);
        }


        $message = __('actions.success.' . ($isNewRole ? 'save' : 'update'), [
            'name' => __('Role'),
        ]);

        return BossResponse::withMessage($message)
            ->withData($role)
            ->asSuccess();
    }


    /**
     * Delete Role.
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function deleteRole(Request $request, Role $role): JsonResponse
    {

        if ($request->boolean('keep_permissions')) {
            $role->delete();
        } else {
            RemoveUsersPermissionsJob::dispatch($role);
        }

        return BossResponse::withMessage(__('actions.success.delete', ['name' => __('Role')]))
            ->asSuccess();
    }
}
