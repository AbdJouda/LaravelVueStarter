<?php

namespace App\Http\Controllers\V1\Auth;

use App\Facades\BossResponse;
use App\Helpers\AccessTokenManager;
use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /**
     * Verify Reset Code
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function verifyResetCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
            'username' => ['required']
        ]);
        $user = $this->getUser($request);

        if ($user)
            $this->validateResetPasswordCode($user, $request);

        return BossResponse::withMessage(__('Your code has been verified successfully.'))
            ->asSuccess();
    }

    /**
     * Validate Reset Password Code
     *
     * @param Authenticatable $user
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    private function validateResetPasswordCode(Authenticatable $user, Request $request): void
    {
        $codeIsValid = $user->accessCodes()
            ->for(AccessCode::PASSWORD_RESET_CODE)
            ->valid()
            ->cursor()
            ->contains(function ($item) use ($request) {
                return Hash::check($request->code, $item->code);
            });

        if (!$codeIsValid)
            throw ValidationException::withMessages([
                'code' => [trans('auth.code')],
            ]);
    }

    /**
     * Reset the user's password.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
            'username' => ['required', 'exists:users,email'],
            'password' => ['required', 'confirmed'],
        ]);


        $user = $this->getUser($request);

        $this->validateResetPasswordCode($user, $request);

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save();

        $user->accessCodes()->for(AccessCode::PASSWORD_RESET_CODE)->delete();

        return BossResponse::withData($user)
            ->withMeta(['authentication' => new AccessTokenManager($user)])
            ->asSuccess();
    }


}
