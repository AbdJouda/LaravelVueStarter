<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetControllerProperties
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {

        $request->attributes->set('user', $request->user());

        $request->attributes->set('sort_by', $request->query('sort_by', 'created_at'));

        $request->attributes->set('sort_dir', $request->query('sort_dir', 'desc'));

        $request->attributes->set('per_page', $this->setRecordsPerPage($request));

        return $next($request);

    }

    /**
     * Set the value of the pagination
     *
     * @param Request $request
     * @return string|int
     */
    private function setRecordsPerPage(Request $request): string|int
    {

        $perPageDefault = env('PER_PAGE_DEFAULT', 6);
        $perPageMin = env('PER_PAGE_MIN', 1);
        $perPageMax = env('PER_PAGE_MAX', 20);

        return min(max($request->query('per_page', $perPageDefault), $perPageMin), $perPageMax);
    }
}
