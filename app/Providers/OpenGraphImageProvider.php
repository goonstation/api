<?php

namespace App\Providers;

use App\Libraries\OpenGraphImage;
use Illuminate\Support\ServiceProvider;

class OpenGraphImageProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenGraphImage::class);
        $this->app->alias(OpenGraphImage::class, 'open-graph-image');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
