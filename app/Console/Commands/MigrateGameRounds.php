<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateGameRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:gamerounds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import game rounds';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Parsing CSV and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/game_rounds.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar1 = $this->output->createProgressBar(count($reader));
        $bar1->start();

        $gameRoundsToInsert = [];
        $eventsToInsert = [];
        foreach ($records as $offset => $record) {
            $gameRound = [];
            $gameRound['id'] = (int) $record['id'];
            $gameRound['server_id'] = $record['server_id'] === 'unknown' ? null : $record['server_id'];
            $gameRound['game_type'] = migval($record['game_type']);
            $gameRound['crashed'] = $record['crashed'] === "\N" ? false : (bool) $record['crashed'];
            $endedAt = $record['ended_at'];
            if ($endedAt === "\N") {
                $endedAt = null;
            }
            if ($endedAt === '0000-00-00 00:00:00') {
                $endedAt = null;
                $gameRound['created_at'] = null;
            } else {
                if ($endedAt) {
                    $endedAt = Carbon::createFromFormat('Y-m-d H:i:s', $endedAt, 'America/Chicago')
                        ->setTimezone('UTC')
                        ->toDateTimeString();
                }

                $createdAt = $record['created_at'];
                if ($createdAt === '0000-00-00 00:00:00') {
                    $createdAt = null;
                } else {
                    // The old goonhub api created game rounds all as EST. lol.
                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt, 'America/Chicago')
                        ->setTimezone('UTC')
                        ->toDateTimeString();
                }
                $gameRound['created_at'] = $createdAt;
            }
            $gameRound['ended_at'] = $endedAt;
            $gameRoundsToInsert[] = $gameRound;

            // Split off station names to their own events
            // We're going to be tracking these separately from now on
            // (So that we get all the name changes in a round)
            $event = [];
            $event['round_id'] = $gameRound['id'];
            $event['name'] = urldecode($record['station_name']);
            $event['created_at'] = $event['updated_at'] = $gameRound['created_at'];
            $eventsToInsert[] = $event;

            $bar1->advance();
        }
        $bar1->finish();

        $this->info(PHP_EOL.'Inserting game rounds'.PHP_EOL);
        $bar2 = $this->output->createProgressBar(count($gameRoundsToInsert) / 1000);
        $bar2->start();
        foreach (array_chunk($gameRoundsToInsert, 1000, true) as $key => $chunk) {
            DB::table('game_rounds')->insert($chunk);
            $bar2->advance();
        }
        $bar2->finish();

        $this->info(PHP_EOL.'Inserting station name events'.PHP_EOL);
        $bar3 = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar3->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_station_names')->insert($chunk);
            $bar3->advance();
        }
        $bar3->finish();

        DB::statement("SELECT setval('game_rounds_id_seq', max(id)) FROM game_rounds;");

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
