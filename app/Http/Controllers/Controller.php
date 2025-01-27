<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * The authenticated user.
     *
     * @var User|null
     */
    protected User|null $user;

    /**
     * Sort records by given column name
     *
     * @var string
     */
    protected string $sortBy;

    /**
     * Sort records by given direction
     *
     * @var string
     */
    protected string $sortDir;

    /**
     * Pagination perPage
     *
     * @var int
     */
    protected int $perPage;


    /**
     * Create new instance
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        $this->user = $request->attributes->get('user');

        $this->sortBy = $request->attributes->get('sort_by');

        $this->sortDir = $request->attributes->get('sort_dir');

        $this->perPage = $request->attributes->get('per_page');

    }

    /**
     * Retrieve user by  email or phone
     *
     * @param $request
     * @param null $user
     * @return Authenticatable|null
     */
    protected function getUser($request, $user = null): ?Authenticatable
    {

        $userName = $request->filled('provider') && $user
            ? $user->email
            : $request->input('username');

        if (filter_var($userName, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $userName];
        } else {
            $credentials = ['phone' => $this->formatPhoneNumber($userName)];
        }

        return Auth::getProvider()
            ->retrieveByCredentials($credentials);
    }



    /**
     * Formatting phone number
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber(string $phone): string
    {
        return '+' . $phone;
    }
}
