<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use tronovav\GeoIP2Update\Client;

class UpdateGeoLite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'license_key' => config('goonhub.maxmind_license_key'),
            'dir' => storage_path('app'),
            'editions' => ['GeoLite2-Country'],
        ]);

        $client->run();

        $errors = $client->errors();
        if (count($errors)) {
            foreach ($errors as $error) {
                echo $error.PHP_EOL;
            }
        } else {
            foreach ($client->updated() as $message) {
                echo $message.PHP_EOL;
            }
        }
    }
}
