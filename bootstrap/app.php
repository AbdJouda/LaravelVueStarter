<?php

use App\Concerns\Versionable;
use App\Helpers\ApiVersionResolver;
use App\Http\Middleware\CheckHasAnyRoleOrPermission;
use App\Http\Middleware\SetControllerProperties;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
        then: function () {

            $requestedVersion = app(ApiVersionResolver::class)->getVersion();

            Route::prefix('api/shared')
                ->middleware('shared')
                ->group(base_path("routes/V{$requestedVersion}/shared.php"));


            Route::prefix('api/admin')
                ->middleware('admin')
                ->group(base_path("routes/V{$requestedVersion}/admin.php"));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('shared', [
            ThrottleRequests::class . ':shared_limiter',
            SetControllerProperties::class
        ]);
        $middleware->group('admin', [
            'auth:sanctum',
            ThrottleRequests::class . ':admin_limiter',
            CheckHasAnyRoleOrPermission::class,
            SetControllerProperties::class,
            SubstituteBindings::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
