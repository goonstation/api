<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        if (config('forcehttps.force_https')) {
            resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');
        }

        $this->routes(function () {
            Route::middleware(['api'])
                ->domain(config('app.api_url'))
                ->group(base_path('routes/api-open.php'));

            Route::middleware(['auth:api', 'api'])
                ->domain(config('app.api_url'))
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
