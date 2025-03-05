<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Spatie\Health\Commands\PauseHealthChecksCommand;
use Spatie\Health\Enums\Status;
use Spatie\Health\Facades\Health;
use Spatie\Health\ResultStores\StoredCheckResults\StoredCheckResult;

class Healthcheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gh:healthcheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the server and all associated services are healthy';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Cache::get(PauseHealthChecksCommand::CACHE_KEY)) {
            return Command::FAILURE;
        }

        $stores = Health::resultStores();
        /** @var \Spatie\Health\ResultStores\EloquentHealthResultStore */
        $store = $stores->first();
        $checkResults = $store->latestResults()?->storedCheckResults;

        $skipChecks = ['UsedDiskSpace', 'DatabaseSize', 'DatabaseConnectionCount'];
        $checkResults = $checkResults ? $checkResults->filter(
            fn (StoredCheckResult $line) => ! in_array($line->name, $skipChecks)
        ) : null;

        $failed = $checkResults ? $checkResults->contains(
            fn (StoredCheckResult $line) => $line->status === Status::failed()->value
        ) : true;

        if ($failed) {
            return Command::FAILURE;
        } else {
            return Command::SUCCESS;
        }
    }
}
