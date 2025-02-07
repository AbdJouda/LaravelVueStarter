<?php

namespace App\Http\Controllers\V1\Admin;

use App\Facades\BossResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserRequest;
use App\Models\User;
use App\Notifications\V1\AutoUserPasswordResetNotification;
use App\Notifications\V1\NewUserCreatedNotification;
use App\Notifications\V1\UserRoleUpdateNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * Retrieve a paginated list of users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {

        $users = User::query()
            ->where($this->user->getKeyName(), '!=', $this->user->getKey())
            ->search($request->query('search'))
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->with('roles')
            ->withCount('permissions')
            ->latest()
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return BossResponse::withData($users)
            ->asSuccess();
    }

    /**
     * Toggle branch active status
     *
     * @param User $user
     * @return JsonResponse
     */
    public function changeUserStatus(User $user): JsonResponse
    {
        $message = $user->toggleActiveStatus();

        return BossResponse::withMessage($message)
            ->withData($user)
            ->asSuccess();
    }

    /**
     * Retrieve a given user details
     *
     * @param User $user
     * @return JsonResponse
     */
    public function geUserDetails(User $user): JsonResponse
    {

        return BossResponse::withData($user->load('roles', 'permissions'))
            ->asSuccess();
    }


    /**
     * Create or Update existing user
     *
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function createUpdateUser(UserRequest $request, User $user): JsonResponse
    {
        $userData = $request->only('name', 'email', 'phone', 'is_active');

        $isNewUser = !$user->exists;

        if ($isNewUser)
            $userData['password'] = $password = Str::password(8);

        $user->forceFill($userData)->save();

        $this->syncUserRoles($request, $user);

        if ($isNewUser)
            $user->notify(new NewUserCreatedNotification($password));

        $message = __('actions.success.' . ($isNewUser ? 'save' : 'update'), [
            'name' => __('User'),
        ]);

        return BossResponse::withMessage($message)
            ->withData($user->load('roles', 'permissions'))
            ->asSuccess();
    }

    /**
     * Synchronize and notify user with his new roles
     *
     * @param UserRequest $request
     * @param User $user
     * @return void
     */
    private function syncUserRoles(UserRequest $request, User $user): void
    {
        $oldPermissions = $user->permissions()->pluck('id')->toArray();
        $newPermissions = $request->permissions;

        $user->syncRoles($request->only('roles'));

        $user->syncPermissions($newPermissions);

        $addedPermissions = array_diff($newPermissions, $oldPermissions);
        $removedPermissions = array_diff($oldPermissions, $newPermissions);

        if ($user->exists && (!empty($addedPermissions) || !empty($removedPermissions))) {
            $user->notify(new UserRoleUpdateNotification());
        }

    }

    /**
     * Reset User Password
     *
     * @param User $user
     * @return JsonResponse
     */
    public function resetUserPassword(User $user): JsonResponse
    {

        $password = Str::password(8);

        $user->fill(['password' => $password])->save();

        $user->notify(new AutoUserPasswordResetNotification($password));

        return BossResponse::withMessage(__('actions.success.action', ['action' => __("reset user's password")]))
            ->withData($user)
            ->asSuccess();
    }
}
