<?php

namespace App\Console\Commands;

use App\Models\Medal;
use App\Models\Player;
use App\Models\PlayerMedal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportMedals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-medals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import medals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting medal import');
        $json = Storage::get('medals.json');
        $users = json_decode($json, true);

        $this->info('Loading medal definitions');
        $medalRecords = Medal::all()->toArray();
        $medals = [];
        foreach ($medalRecords as $medal) {
            $medals[$medal['title']] = $medal;
        }

        $this->info('Loading existing player earned medal records');
        $earnedMedalRecords = PlayerMedal::all()->toArray();
        $earnedMedals = [];
        foreach ($earnedMedalRecords as $earnedMedal) {
            $earnedMedals[$earnedMedal['player_id'].'-'.$earnedMedal['medal_id']] = $earnedMedal;
        }

        $this->info('Loading player records');
        $playersRecords = Player::all()->toArray();
        $players = [];
        foreach ($playersRecords as $player) {
            $players[$player['ckey']] = $player;
        }

        $playerMedalsToInsert = [];

        $this->info('Processing medal data');
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();
        foreach ($users as $ckey => $scrapedMedals) {
            if (empty($scrapedMedals)) {
                continue;
            }

            $player = null;
            if (array_key_exists($ckey, $players)) {
                $player = $players[$ckey];
            }

            if (! $player) {
                // $this->warn("No player record found for: $ckey");
                continue;
            }

            // $this->info("Processing medals for $ckey");
            foreach ($scrapedMedals as $scrapedMedal) {
                $medal = null;
                if (array_key_exists($scrapedMedal['Name'], $medals)) {
                    $medal = $medals[$scrapedMedal['Name']];
                }

                if (! $medal) {
                    $this->warn("No medal record found for: {$scrapedMedal['Name']} ($ckey)");
                    continue;
                }

                if (array_key_exists($player['id'].'-'.$medal['id'], $earnedMedals)) {
                    // $this->info("\tPlayed $ckey has already earned medal ".$scrapedMedal['Name']);
                    continue;
                }

                // $this->info("\tInserting medal: ({$medal['id']}) {$medal['title']}");

                $earnedAt = Carbon::parse($scrapedMedal['Date'], -7)
                    ->setTimezone('UTC')
                    ->toISOString();

                $playerMedalsToInsert[] = [
                    'player_id' => $player['id'],
                    'medal_id' => $medal['id'],
                    'created_at' => $earnedAt,
                    'updated_at' => $earnedAt,
                ];
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info(PHP_EOL.'Inserting player medals');
        $bar = $this->output->createProgressBar(count($playerMedalsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($playerMedalsToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_medals')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);
        return Command::SUCCESS;
    }
}
