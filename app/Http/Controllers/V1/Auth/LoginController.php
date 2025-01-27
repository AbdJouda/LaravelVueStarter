<?php

namespace App\Http\Controllers\V1\Auth;

use App\Facades\BossResponse;
use App\Helpers\AccessTokenManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class LoginController extends Controller
{
    /**
     * Attempt to authenticate user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {

        return $request->filled('provider')
            ? $this->loginViaSocial($request)
            : $this->loginViaUsername($request);

    }

    /**
     * Login user via social access token
     *
     * @param $request
     * @return JsonResponse
     * @throws ValidationException
     */
    private function loginViaSocial($request): JsonResponse
    {

        $this->validateSocialLoginFields($request);

        $socialUser = $this->getUserViaToken($request);

        $user = $this->getUser($request, $socialUser);

        if (!$user || !$user->isActive()) {
            $this->throwFailedAuthenticationException($request, !$user?->isActive());
        }

        return $this->successfulResponse($user);

    }

    /**
     * Validate social login fields.
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    protected function validateSocialLoginFields(Request $request): void
    {
        $request->validate([
            'provider' => ['required', 'in:google,facebook,apple'],
            'code' => ['required'],
        ]);
    }


    /**
     * Retrieve user by their social token
     *
     * @param $request
     * @return User
     */
    private function getUserViaToken($request): User
    {

        $provider = $request->input('provider') === 'apple'
            ? 'sign-in-with-apple'
            : $request->input('provider');


        return Socialite::driver($provider)->userFromToken($request->input('code'));

    }


    /**
     * Throw a failed authentication validation exception.
     *
     * @param $request
     * @param bool $isSuspendedAccount
     * @return void
     * @throws ValidationException
     */
    protected function throwFailedAuthenticationException($request, bool $isSuspendedAccount = false): void
    {
        RateLimiter::hit($this->throttleKey($request), 300);

        throw ValidationException::withMessages([
            'username' => [trans($isSuspendedAccount ? 'auth.suspended' : 'auth.failed')],
        ]);

    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @param Request $request
     * @return string
     */
    public function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('username')) . '|' . $request->ip());
    }

    /**
     * Handle user success login
     *
     * @param $user
     * @return JsonResponse
     */
    protected function successfulResponse($user): JsonResponse
    {
        /**@var User $user */
        return BossResponse::withData(UserResource::make($user->append('hasNewNotifications')))
            ->withMeta(new AccessTokenManager($user))
            ->asSuccess();
    }

    /**
     * Attempt to authenticate User
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function loginViaUsername(Request $request): JsonResponse
    {
        $this->ensureIsNotRateLimited($request);

        $this->validateUsernameLoginFields($request);

        $user = $this->validateCredentials($request);

        RateLimiter::clear($this->throttleKey($request));

        return $this->successfulResponse($user);

    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Validate user email login inputs
     *
     * @param Request $request
     * @return void
     */
    protected function validateUsernameLoginFields(Request $request): void
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);
    }

    /**
     * Attempt to validate the incoming credentials.
     *
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    protected function validateCredentials(Request $request): mixed
    {

        $user = $this->getUser($request);

        if ($user) {
            $validateCredentials = Auth::getProvider()
                ->validateCredentials($user, ['password' => $request->input('password')]);

        }

        if (!$user || !$validateCredentials || !$user->isActive()) {
            $this->throwFailedAuthenticationException($request, ($user && !$user->isActive()));
        }

        return $user;
    }

    /**
     * Logout User
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {

        $request->user()->tokens()->delete();

        return BossResponse::asSuccess();
    }
}
