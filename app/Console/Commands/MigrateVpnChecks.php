<?php

namespace App\Console\Commands;

use App\Models\GameAdmin;
use App\Models\VpnWhitelist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateVpnChecks extends Command
{
    use HasGoonTeamHandling;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:vpnchecks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function ckeyIzeAdmin($adminCkey)
    {
        if (str_ends_with($adminCkey, '(Discord)')) {
            $adminCkey = str_replace(' (Discord)', '', $adminCkey);
        }
        if (str_ends_with($adminCkey, '(AUTO)')) {
            $adminCkey = str_replace(' (AUTO)', '', $adminCkey);
        }

        return ckey($adminCkey);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing vpn_checks.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/vpn_checks.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $vpnChecksToInsert = [];
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $vpnChecksToInsert[] = [
                'ip' => $record['ip'],
                'service' => 'ipqualityscore',
                'response' => $record['response'],
                'created_at' => $record['created_at'],
                'updated_at' => $record['updated_at'],
            ];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing vpn_check_whitelist.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/vpn_check_whitelist.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $this->getAdminTeamSheet();
        $adminsByDiscord = $this->getTeamAdminsByDiscordName();
        $gameAdminsRecords = GameAdmin::all()->toArray();
        $gameAdmins = [];
        foreach ($gameAdminsRecords as $gameAdmin) {
            $gameAdmins[$gameAdmin['ckey']] = $gameAdmin;
        }

        $botAdmins = [
            'autobanner',
            'nerdbanner',
            'autotagger',
            'fpjs',
            'auto',
            'vpnblocker',
            '',
        ];

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $adminCkey = $this->ckeyIzeAdmin($record['akey']);
            $gameAdmin = null;
            if (array_key_exists($adminCkey, $gameAdmins)) {
                $gameAdmin = $gameAdmins[$adminCkey];
            }

            if (! $gameAdmin) {
                if (in_array($adminCkey, $botAdmins)) {
                    $gameAdmin = $gameAdmins['bot'];
                } elseif (array_key_exists($adminCkey, $adminsByDiscord)) {
                    $gameAdmin = $gameAdmins[$adminsByDiscord[$adminCkey]];
                } else {
                    // echo 'No admin entry for '.$adminCkey.PHP_EOL;
                    continue;
                }
            }

            $whitelist = new VpnWhitelist();
            $whitelist->timestamps = false;
            $whitelist->game_admin_id = $gameAdmin['id'];
            $whitelist->ckey = $record['ckey'];
            $whitelist->created_at = $record['created_at'];
            $whitelist->updated_at = $record['updated_at'];
            $whitelist->save();

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting vpn checks'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($vpnChecksToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($vpnChecksToInsert, 1000, true) as $key => $chunk) {
            DB::table('vpn_checks')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
