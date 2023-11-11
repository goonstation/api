<?php

namespace App\Console\Commands;

use GeoIp2\Database\Reader as GeoReader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigratePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:players';

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
        $this->info('Processing players_with_connections.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/players_with_connections.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $geoReader = new GeoReader(storage_path('app').'/GeoLite2-Country/GeoLite2-Country.mmdb');

        $players = [];

        $connectionsToInsert = [];
        $participationsToInsert = [];
        $playtimesToInsert = [];

        $playerCount = 0;
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $player = null;
            if (! array_key_exists($record['ckey'], $players)) {
                $playerCount++;
                $player = [];
                $player['id'] = $playerCount;
                $player['ckey'] = $record['ckey'];
                $player['created_at'] = $record['connection_created_at'];
                $player['updated_at'] = $record['connection_created_at'];
                $players[$record['ckey']] = $player;
            } else {
                $player = $players[$record['ckey']];
            }

            if (! empty($record['ip']) && ! empty($record['comp_id'])) {
                $geoRecord = null;
                try {
                    $geoRecord = $geoReader->country($record['ip']);
                } catch (\Exception $e) {
                    // pass
                }

                $connection = [];
                $connection['player_id'] = $player['id'];
                $connection['ip'] = $record['ip'];
                $connection['comp_id'] = $record['comp_id'];
                $connection['country'] = $geoRecord ? $geoRecord->country->name : null;
                $connection['country_iso'] = $geoRecord ? $geoRecord->country->isoCode : null;
                $connection['legacy_data'] = json_encode([
                    'server_id' => migval($record['server_id']),
                    'rp_mode' => (bool) migval($record['rp_mode']),
                ]);
                $connection['created_at'] = $connection['updated_at'] = $record['connection_created_at'];
                $connectionsToInsert[] = $connection;
            }

            // if ($offset > 0 && $offset % 250000 == 0) sleep(5);
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing player_participations.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/player_participations.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            // a participation without a connection should be impossible but eh
            if (! array_key_exists($record['ckey'], $players)) {
                continue;
            }
            $player = $players[$record['ckey']];

            $participation = [];
            $participation['player_id'] = $player['id'];
            $participation['legacy_data'] = json_encode([
                'server_id' => migval($record['server_id']),
                'rp_mode' => (bool) migval($record['rp_mode']),
            ]);
            $participation['created_at'] = $participation['updated_at'] = $record['created_at'];
            $participationsToInsert[] = $participation;

            // if ($offset > 0 && $offset % 250000 == 0) sleep(5);
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing player_playtime.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/player_playtime.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            // a playtime without a connection should be impossible but ehhhhh
            if (! array_key_exists($record['ckey'], $players)) {
                continue;
            }
            $player = $players[$record['ckey']];

            $playtime = [];
            $playtime['player_id'] = $player['id'];
            $playtime['seconds_played'] = $record['seconds_played'];
            $playtime['server_id'] = $record['server_id'];
            $playtimesToInsert[] = $playtime;

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Processing player_versions.csv'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/player_versions.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            // a version without a connection should be impossible but ehhhhh
            if (! array_key_exists($record['ckey'], $players)) {
                continue;
            }
            $player = &$players[$record['ckey']];
            $player['byond_major'] = $record['byond_major'];
            $player['byond_minor'] = $record['byond_minor'];

            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting players'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($players) / 1000);
        $bar->start();
        foreach (array_chunk($players, 1000, true) as $key => $chunk) {
            $toInsert = [];
            foreach ($chunk as $key => $value) {
                // this signifies mismatched players and versions dumps
                // aka we dumped versions too long before players, and there's more players than versions now
                // this should only happen during local dev because im not shutting down the existing api
                if (! array_key_exists('byond_major', $value)) {
                    $value['byond_major'] = null;
                    $value['byond_minor'] = null;
                }
                $toInsert[] = $value;
            }
            DB::table('players')->insert($toInsert);
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting player connections'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($connectionsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($connectionsToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_connections')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting player participations'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($participationsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($participationsToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_participations')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting player playtime'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($playtimesToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($playtimesToInsert, 1000, true) as $key => $chunk) {
            DB::table('player_playtime')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();

        DB::statement("SELECT setval('players_id_seq', max(id)) FROM players;");

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
