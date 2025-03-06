<?php

namespace App\Checks;

use Illuminate\Support\Facades\Http;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class ReverbCheck extends Check
{
    public function run(): Result
    {
        $result = Result::make();

        $host = config('reverb.servers.reverb.host');
        $port = config('reverb.servers.reverb.port');

        try {
            Http::get("$host:$port");
        } catch (\Throwable) {
            return $result
                ->failed('Reverb is not running')
                ->shortSummary('Not running');
        }

        return $result->ok()->shortSummary('Running');
    }
}
