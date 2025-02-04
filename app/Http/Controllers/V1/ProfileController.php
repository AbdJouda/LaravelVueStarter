<?php

namespace App\Http\Controllers\V1;

use App\Events\AccountDeletionRequested;
use App\Facades\BossResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Retrieve the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        $user = $this->user;

        return BossResponse::withData($user)
            ->asSuccess();
    }

    /**
     * Retrieve the currently authenticated user roles and permissions
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        return BossResponse::withData($this->user->load('roles', 'permissions'))
            ->asSuccess();
    }


    /**
     * Update the currently authenticated user's information.
     *
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(ProfileRequest $request): JsonResponse
    {
        $userData = $request->validated();


        if ($request->hasFile('profile_photo')) {
            $userData['profile_photo_path'] = $this->user->processFile($userData['profile_photo']);
        }

        $this->user->update($userData);


        return BossResponse::withMessage(__('actions.success.update', ['name' => __('Profile')]))
            ->withData($this->user)
            ->asSuccess();
    }


    /**
     * Update the currently authenticated user's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {

        $request->validate([
            'password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)->numbers()->symbols(), 'different:password'],
        ], [
            'new_password.different' => __('passwords.different'),
        ]);


        $this->user->update(['password' => $request->input('new_password')]);

        return BossResponse::withMessage(__('actions.success.update', ['name' => __('Password')]))
            ->asSuccess();
    }

    /**
     * Request account deletion
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function requestDeletion(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'current_password']
        ]);

        $this->user->delete();

        event(new AccountDeletionRequested($this->user));

        return BossResponse::withMessage(__('actions.success.delete', ['name' => __('Account')]))
            ->asSuccess();
    }

    /**
     * Retrieve User's notifications.
     *
     * @return JsonResponse
     */
    public function getNotifications(): JsonResponse
    {

        $notifications = $this->user
            ->notifications()
            ->paginate($this->perPage);

        $this->markNewNotificationsAsRead($notifications->items());

        return BossResponse::withData($notifications)
            ->asSuccess();
    }


    /**
     * Mark new fetched notifications as read.
     *
     * @param array $notifications
     * @return void
     */
    private function markNewNotificationsAsRead(array $notifications): void
    {

        DatabaseNotification::query()
            ->whereIn('id', Arr::pluck($notifications,'id'))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

    }

}
