<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Helpers\JsonResponseHandler;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ExceptionHandler::class,
            Handler::class
        );

        if (!$this->app->isProduction()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->singleton('boss-response', function (Container $app) {
            return new JsonResponseHandler($app['request'], new JsonResponse);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['auth:sanctum']]);

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        $this->configureRateLimiter();
    }

    /**
     * Configure the rate limiters for the application.
     *
     */
    protected function configureRateLimiter(): void
    {
        RateLimiter::for('shared_limiter', function (Request $request){
            return Limit::perMinute(60)->by($request->ip());
        });
        RateLimiter::for('admin_limiter', function (Request $request){
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
