<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:metadata';

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
        $this->info('Processing player_metadata.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/player_metadata.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $playersRecords = Player::all()->toArray();
        $players = [];
        foreach ($playersRecords as $player) {
            $players[$player['ckey']] = $player;
        }

        $metadataToInsert = [];
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $player = null;
            if (array_key_exists($record['ckey'], $players)) {
                $player = $players[$record['ckey']];
            }

            $metadataToInsert[] = [
                'player_id' => $player ? $player['id'] : null,
                'ckey' => $record['ckey'],
                'data' => $record['data'],
                'created_at' => $record['created_at'],
                'updated_at' => $record['updated_at'],
            ];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting metadata'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($metadataToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($metadataToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_metadata')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
