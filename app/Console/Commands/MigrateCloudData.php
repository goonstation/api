<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateCloudData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:clouddata';

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
        $this->info('Parsing byond_cloud_data.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/spacebee/byond_cloud_data.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $playersRecords = Player::all()->toArray();
        $players = [];
        foreach ($playersRecords as $player) {
            $players[$player['ckey']] = $player;
        }

        $uniqueEntries = [];
        $dataToInsert = [];
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

            $uniqueEntryKey = $player['id'].$record['key'];
            if (array_key_exists($uniqueEntryKey, $uniqueEntries)) {
                continue;
            }
            $uniqueEntries[$uniqueEntryKey] = true;

            $dataToInsert[] = [
                'player_id' => $player['id'],
                'key' => $record['key'],
                'value' => $record['value'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Parsing savefiles.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/spacebee/savefiles.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $uniqueEntries = [];
        $savefilesToInsert = [];
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

            $uniqueEntryKey = $player['id'].$record['savename'];
            if (array_key_exists($uniqueEntryKey, $uniqueEntries)) {
                continue;
            }
            $uniqueEntries[$uniqueEntryKey] = true;

            $savefilesToInsert[] = [
                'player_id' => $player['id'],
                'name' => $record['savename'],
                'data' => $record['savedata'],
                'created_at' => $record['savetime'],
                'updated_at' => $record['savetime'],
            ];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting player data'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($dataToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($dataToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_data')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting savefiles'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($savefilesToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($savefilesToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_saves')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
