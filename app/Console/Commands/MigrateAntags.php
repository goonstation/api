<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateAntags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:antags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing player_antags.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/player_antags.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $playersRecords = Player::all()->toArray();
        $players = [];
        foreach ($playersRecords as $player) {
            $players[$player['ckey']] = $player;
        }

        $antagsToInsert = [];
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $player = null;
            if (array_key_exists($record['ckey'], $players)) {
                $player = $players[$record['ckey']];
            }
            if (! $player) {
                continue;
            }

            $antagsToInsert[] = [
                'player_id' => $player['id'],
                'antag_role' => $record['role'],
                'late_join' => $record['latejoin'],
                'weight_exempt' => $record['exempt'] === "\N" ? null : $record['exempt'],
                'created_at' => $record['created_at'],
                'updated_at' => $record['created_at'],
            ];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting antags'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($antagsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($antagsToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_antags')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
