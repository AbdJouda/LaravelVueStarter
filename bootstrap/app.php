<?php

use App\Http\Middleware\CheckHasAnyRoleOrPermission;
use App\Http\Middleware\SetControllerProperties;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
        then: function () {
            $requestedVersion = Container::getInstance()->make('request')->header('api-version', 1);

            $requestedVersion = in_array($requestedVersion, Config::get('settings.api.versions'))
                ? $requestedVersion
                : Config::get('settings.api.current_version');

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
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
