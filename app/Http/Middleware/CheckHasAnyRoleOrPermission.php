<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHasAnyRoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next): Response
    {
//        /**@var \App\Models\User $user */
//        $user = $request->user();
//
//        if ($user->roles()->doesntExist() && $user->permissions()->doesntExist()) {
//            throw new AuthorizationException;
//        }

        return $next($request);
    }
}
