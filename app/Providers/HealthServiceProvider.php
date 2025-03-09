<?php

namespace App\Providers;

use Ahtinurme\OctaneCheck;
use App\Checks\ReverbCheck;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Checks\Checks\HorizonCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\RedisMemoryUsageCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Check::macro('isEnv', function (string|array $envs) {
            /** @var \Spatie\Health\Checks\Check $this */
            return $this->if(fn () => app()->environment($envs));
        });

        Health::checks([
            UsedDiskSpaceCheck::new()->isEnv(['production', 'staging']),
            DatabaseCheck::new(),
            CacheCheck::new(),
            OptimizedAppCheck::new(),
            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(100)
                ->failWhenMoreConnectionsThan(200),
            DatabaseSizeCheck::new()
                ->failWhenSizeAboveGb(200.0),
            HorizonCheck::new()->isEnv(['production', 'staging']),
            QueueCheck::new()->isEnv(['production', 'staging']),
            RedisCheck::new()->isEnv(['production', 'staging']),
            RedisMemoryUsageCheck::new()
                ->warnWhenAboveMb(900)
                ->failWhenAboveMb(1000),
            ScheduleCheck::new()->isEnv(['production', 'staging']),
            OctaneCheck::new()->isEnv(['production', 'staging']),
            ReverbCheck::new(),
        ]);
    }
}
