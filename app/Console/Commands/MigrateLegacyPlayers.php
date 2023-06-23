<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateLegacyPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:playerslegacy';

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
        $playersRecords = Player::all()->toArray();
        $players = [];
        foreach ($playersRecords as $player) {
            $players[$player['ckey']] = $player;
        }

        $playersToInsert = [];

        $this->info('Processing tickets.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/ai_laws.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['ai_key']);
            if (! $playerCkey || array_key_exists($playerCkey, $players)) {
                continue;
            }
            if (! array_key_exists($playerCkey, $playersToInsert)) {
                $playersToInsert[$playerCkey] = ['ckey' => $playerCkey];
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing traitors.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/traitors.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['mob_byond_key']);
            if (! $playerCkey || array_key_exists($playerCkey, $players)) {
                continue;
            }
            if (! array_key_exists($playerCkey, $playersToInsert)) {
                $playersToInsert[$playerCkey] = ['ckey' => $playerCkey];
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing deaths.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/deaths.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['mob_byond_key']);
            if (! $playerCkey || array_key_exists($playerCkey, $players)) {
                continue;
            }
            if (! array_key_exists($playerCkey, $playersToInsert)) {
                $playersToInsert[$playerCkey] = ['ckey' => $playerCkey];
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing fines.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/fines.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['issuer_byond_key']);
            if (! $playerCkey || array_key_exists($playerCkey, $players)) {
                continue;
            }
            if (! array_key_exists($playerCkey, $playersToInsert)) {
                $playersToInsert[$playerCkey] = ['ckey' => $playerCkey];
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing tickets.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/tickets.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['issuer_byond_key']);
            if (! $playerCkey || array_key_exists($playerCkey, $players)) {
                continue;
            }
            if (! array_key_exists($playerCkey, $playersToInsert)) {
                $playersToInsert[$playerCkey] = ['ckey' => $playerCkey];
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting players'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($playersToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($playersToInsert, 1000, true) as $key => $chunk) {
            DB::table('players')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
