<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $version = config('goonhub.geoip_update_version');
        $homePath = getenv('HOME');
        $binPath = "$homePath/geoipupdate_{$version}";

        if (!is_dir($binPath)) {
            $fileName = "geoipupdate_{$version}_linux_amd64";
            $fileNameWithExt = "$fileName.tar.gz";
            $downloadUrl = "https://github.com/maxmind/geoipupdate/releases/download/v$version/$fileNameWithExt";
            copy($downloadUrl, "$homePath/$fileNameWithExt");
            exec("tar -xzf $homePath/$fileNameWithExt --one-top-level=$homePath");
            rename("$homePath/$fileName", $binPath);
            unlink("$homePath/$fileNameWithExt");
        }

        $configFile = storage_path('app') . '/GeoIP.conf';
        $outputDir = storage_path('app') . '/GeoLite2';
        $lockFile = storage_path('app') . '/.geoipupdate.lock';
        $accountId = config('goonhub.maxmind_account_id');
        $licenseKey = config('goonhub.maxmind_license_key');
        file_put_contents($configFile,
            "AccountID $accountId\n".
            "LicenseKey $licenseKey\n".
            "EditionIDs GeoLite2-ASN GeoLite2-City GeoLite2-Country\n".
            "DatabaseDirectory $outputDir\n".
            "LockFile $lockFile"
        );

        if (!is_dir($outputDir)) mkdir($outputDir);
        exec("$binPath/geoipupdate -f $configFile -v");
    }
}
