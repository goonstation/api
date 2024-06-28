<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;
use App\Policies\TestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                // Admins can do anything
                return true;
            }
        });

        Gate::define('viewApiDocs', function ($user = null) {
            return true;
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->isGameAdmin();
        });

        Gate::define('view-test', [TestPolicy::class, 'view']);
    }
}
