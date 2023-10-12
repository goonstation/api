<?php

namespace App\Console\Commands;

use App\Models\GameAdmin;
use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateNotes extends Command
{
    use HasGoonTeamHandling;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:notes';

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
        $playersRecords = Player::all()->toArray();
        $players = [];
        foreach ($playersRecords as $player) {
            $players[$player['ckey']] = $player;
        }
        $this->getAdminTeamSheet();
        $adminsByDiscord = $this->getTeamAdminsByDiscordName();
        $gameAdminsRecords = GameAdmin::all()->toArray();
        $gameAdmins = [];
        foreach ($gameAdminsRecords as $gameAdmin) {
            $gameAdmins[$gameAdmin['ckey']] = $gameAdmin;
        }
        $notesToInsert = [];

        $botAdmins = [
            'autobanner',
            'nerdbanner',
            'autotagger',
            'fpjs',
            'auto',
            'vpnblocker',
            '',
        ];

        $this->info('Parsing notes CSV and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/notes.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $adminCkey = $this->ckeyIzeAdmin($record['admin_ckey']);

            $gameAdmin = null;
            if (array_key_exists($adminCkey, $gameAdmins)) {
                $gameAdmin = $gameAdmins[$adminCkey];
            }
            $player = null;
            if (array_key_exists($record['ckey'], $players)) {
                $player = $players[$record['ckey']];
            }

            if (! $gameAdmin) {
                if (in_array($adminCkey, $botAdmins)) {
                    $gameAdmin = $gameAdmins['bot'];
                } elseif (array_key_exists($adminCkey, $adminsByDiscord)) {
                    $gameAdmin = $gameAdmins[$adminsByDiscord[$adminCkey]];
                }
            }

            $legacyData = [];
            if (! empty($record['oldserver']) && $record['oldserver'] !== "\N") {
                $legacyData['oldserver'] = $record['oldserver'];
            }

            $note = [];
            if (! empty($player)) {
                $note['player_id'] = $player['id'];
                $note['ckey'] = null;
            } else {
                $note['player_id'] = null;
                $note['ckey'] = $record['ckey'];
            }
            if (! empty($gameAdmin)) {
                $note['game_admin_id'] = $gameAdmin['id'];
            } else {
                $note['game_admin_id'] = null;
                $legacyData['game_admin_ckey'] = $adminCkey;
            }

            $serverId = strtolower($record['server_id']);
            if ($serverId === "\n" || $serverId === 'discord') $serverId = null;

            $note['server_id'] = strtolower($record['server_id']);
            $note['note'] = urldecode(stripslashes($record['note']));
            $note['legacy_data'] = json_encode($legacyData);
            $note['created_at'] = $record['created_at'];
            $notesToInsert[] = $note;

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting notes'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($notesToInsert));
        $bar->start();
        foreach ($notesToInsert as $key => $note) {
            try {
                DB::table('player_notes')->insert($note);
            } catch (\Exception $e) {
                // get turbofucked shitass ancient notes
                // (note: this only skips like 8 very old, very useless notes)
                // $this->info('Bad entry ID: '. $key+1);
                // echo $e->getMessage().PHP_EOL;
            }
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
