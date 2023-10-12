<?php

namespace App\Console\Commands;

use App\Models\Ban;
use App\Models\GameAdmin;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateBans extends Command
{
    use HasGoonTeamHandling;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:bans';

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
        $this->info('Parsing CSV and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/bans.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();

        $botAdmins = [
            'autobanner',
            'nerdbanner',
            'autotagger',
            'fpjs',
            'auto',
            'vpnblocker',
            '',
        ];

        $this->getAdminTeamSheet();
        $adminsByDiscord = $this->getTeamAdminsByDiscordName();
        $gameAdminsRecords = GameAdmin::all()->toArray();
        $gameAdmins = [];
        foreach ($gameAdminsRecords as $gameAdmin) {
            $gameAdmins[$gameAdmin['ckey']] = $gameAdmin;
        }

        $bansToInsert = [];
        $banDetailsToInsert = [];
        foreach ($records as $offset => $record) {
            $banId = null;
            $deletedAt = null;
            if (array_key_exists($record['reason'], $bansToInsert)) {
                $banId = $bansToInsert[$record['reason']]['id'];
                $deletedAt = $bansToInsert[$record['reason']]['deleted_at'];
            } else {
                $expiresAt = null;
                $requiresAppeal = false;
                if ((int) $record['timestamp'] > 0) {
                    $byondEpochStart = Carbon::createFromFormat('d/m/Y H:i:s', '01/01/2000 00:00:00');
                    $expiresAt = $byondEpochStart->addMinutes((int) $record['timestamp']);
                } else if ((int) $record['timestamp'] === -1) {
                    $requiresAppeal = true;
                }

                $adminCkey = $record['original_admin_ckey'];
                if ($adminCkey === 'N/A') {
                    $adminCkey = $record['admin_ckey'];
                }
                $adminCkey = $this->ckeyIzeAdmin($adminCkey);

                $gameAdmin = null;
                if (array_key_exists($adminCkey, $gameAdmins)) {
                    $gameAdmin = $gameAdmins[$adminCkey];
                }

                if (! $gameAdmin) {
                    if (in_array($adminCkey, $botAdmins)) {
                        $gameAdmin = $gameAdmins['bot'];
                    } elseif (array_key_exists($adminCkey, $adminsByDiscord)) {
                        $gameAdmin = $gameAdmins[$adminsByDiscord[$adminCkey]];
                    }
                }

                $serverId = $record['server_id'] === "\N" ? null : $record['server_id'];
                if ($serverId === 'rp') $serverId = null;

                $ban = new Ban();
                $ban->timestamps = false;
                $ban->game_admin_id = $gameAdmin ? $gameAdmin['id'] : null;
                $ban->reason = stripslashes($record['reason']);
                $ban->server_id = $serverId;
                $ban->expires_at = $expiresAt;
                $ban->created_at = $record['created_at'];
                $ban->updated_at = $record['updated_at'];
                $ban->deleted_at = ! $record['removed'] ? null : $record['updated_at']; // we assume "removed" bans were removed when last modified
                $ban->requires_appeal = $requiresAppeal;
                $ban->save();
                $banId = $ban->id;
                $deletedAt = $ban->deleted_at;
                $bansToInsert[$record['reason']] = [
                    'id' => $banId,
                    'deleted_at' => $deletedAt,
                ];
            }

            $ip = $record['ip'] === 'N/A' ? null : $record['ip'];
            if (! is_null($ip) && ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ip = null;
            }

            $banDetail = [];
            $banDetail['ban_id'] = $banId;
            $banDetail['ckey'] = $record['ckey'] === 'N/A' ? null : $record['ckey'];
            $banDetail['ip'] = $ip;
            $banDetail['comp_id'] = $record['comp_id'] === 'N/A' ? null : $record['comp_id'];
            $banDetail['created_at'] = $record['created_at'];
            $banDetail['updated_at'] = $record['updated_at'];
            $banDetail['deleted_at'] = $deletedAt;
            $banDetailsToInsert[] = $banDetail;

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting ban details'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($banDetailsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($banDetailsToInsert, 1000, true) as $key => $chunk) {
            DB::table('ban_details')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
