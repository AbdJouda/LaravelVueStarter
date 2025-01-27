<?php

namespace App\Http\Controllers\V1\Auth;

use App\Facades\BossResponse;
use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\User;
use App\Notifications\V1\ForgotPasswordNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Send a reset email to the given user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'username' => ['required'],
        ]);

        $user = $this->getUser($request);

        if ($user) {
            $this->sendResetPasswordNotification($user);
        }

        return BossResponse::withMessage(__('passwords.forgot'))
            ->asSuccess();

    }

    /**
     * Generate Random Reset Code and Send Notification to User
     *
     * @param User $user
     * @return void
     */
    private function sendResetPasswordNotification(Authenticatable $user): void
    {
        $code = $user->generateCode(AccessCode::PASSWORD_RESET_CODE);

        $url = url()->query('/reset-password', ['email' => $user->email, 'code' => $code]);

        $user->notify(new ForgotPasswordNotification($code, urldecode($url)));

    }


}
