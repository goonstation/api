<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateMapSwitches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:mapswitches';

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
        $this->info('Processing map_votes.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/map_votes.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $mapSwitchesToInsert = [];
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $mapSwitchesToInsert[] = [
                'server_id' => $record['server_id'],
                'map' => $record['map'],
                'created_at' => $record['created_at'],
            ];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting map switches'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($mapSwitchesToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($mapSwitchesToInsert, 1000, true) as $key => $chunk) {
            DB::table('map_switches')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
