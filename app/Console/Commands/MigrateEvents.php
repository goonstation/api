<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class MigrateEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:events';

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

        $eventsToInsert = [];

        // Start AI Laws

        $this->info('Parsing ai_laws.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/ai_laws.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $aiCkey = ckey(migval($record['ai_key']));
            $player = null;
            if (array_key_exists($aiCkey, $players)) {
                $player = $players[$aiCkey];
            }

            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['player_id'] = $player ? $player['id'] : null;
            $event['ai_name'] = migval($record['ai_name']);
            $event['law_number'] = migval($record['law_number']);
            $event['law_text'] = migval($record['law_text']);
            $event['uploader_name'] = migval($record['uploader_name']);
            $event['uploader_ckey'] = ckey(migval($record['uploader_key']));
            $event['uploader_job'] = migval($record['uploader_job']);
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting ai law events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_ai_laws')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End AI laws
        // Start bees

        $this->info(PHP_EOL.PHP_EOL.'Parsing bees.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/bees.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['name'] = migval($record['name']);
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting bee spawn events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_bee_spawns')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End bees
        // Start deaths

        $this->info(PHP_EOL.PHP_EOL.'Parsing deaths.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/deaths.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['mob_byond_key']);
            $player = null;
            if (array_key_exists($playerCkey, $players)) {
                $player = $players[$playerCkey];
            }

            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['player_id'] = $player ? $player['id'] : null;
            $event['mob_name'] = migval($record['mob_name']);
            $event['mob_job'] = migval($record['mob_job']);
            $event['x'] = migval($record['x']);
            $event['y'] = migval($record['y']);
            $event['z'] = migval($record['z']);
            $event['bruteloss'] = migval($record['bruteloss']);
            $event['fireloss'] = migval($record['fireloss']);
            $event['toxloss'] = migval($record['toxloss']);
            $event['oxyloss'] = migval($record['oxyloss']);
            $event['gibbed'] = migval($record['gibbed']) === '1' ? true : false;
            $event['last_words'] = isset($record['last_words']) ? migval($record['last_words']) : null;
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting death events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_deaths')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End deaths
        // Start fines

        $this->info(PHP_EOL.PHP_EOL.'Parsing fines.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/fines.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['target_byond_key']);
            $player = null;
            if (array_key_exists($playerCkey, $players)) {
                $player = $players[$playerCkey];
            }

            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['player_id'] = $player ? $player['id'] : null;
            $event['target'] = migval($record['target']);
            $event['reason'] = migval($record['reason']);
            $event['issuer'] = migval($record['issuer']);
            $event['issuer_job'] = migval($record['issuer_job']);
            $event['issuer_ckey'] = ckey(migval($record['issuer_byond_key']));
            $event['amount'] = migval($record['amount']);
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting fine events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_fines')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End fines
        // Start tickets

        $this->info(PHP_EOL.PHP_EOL.'Parsing tickets.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/tickets.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['target_byond_key']);
            $player = null;
            if (array_key_exists($playerCkey, $players)) {
                $player = $players[$playerCkey];
            }

            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['player_id'] = $player ? $player['id'] : null;
            $event['target'] = migval($record['target']);
            $event['reason'] = migval($record['reason']);
            $event['issuer'] = migval($record['issuer']);
            $event['issuer_job'] = migval($record['issuer_job']);
            $event['issuer_ckey'] = ckey(migval($record['issuer_byond_key']));
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting ticket events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_tickets')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End tickets
        // Start gauntlet high scores

        $this->info(PHP_EOL.PHP_EOL.'Parsing gauntlet_high_scores.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/gauntlet_high_scores.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['names'] = migval($record['names']);
            $event['score'] = migval($record['score']);
            $event['highest_wave'] = migval($record['highest_wave']);
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting gauntlet high score events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_gauntlet_high_scores')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End gauntlet high scores
        // Start antags

        $this->info(PHP_EOL.PHP_EOL.'Parsing traitors.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/traitors.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $traitorRecords = [];
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['mob_byond_key']);
            $player = null;
            if (array_key_exists($playerCkey, $players)) {
                $player = $players[$playerCkey];
            } else {
                // $this->info('No player record for antag: ' . $playerCkey);
                continue;
            }

            if ($player) {
                $traitorRecords['traitor'.$record['id']] = [
                    'roundId' => $record['round_id'],
                    'playerId' => $player['id'],
                ];
            }

            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['player_id'] = $player ? $player['id'] : null;
            $event['mob_name'] = migval($record['mob_name']);
            $event['mob_job'] = migval($record['mob_job']);
            $event['traitor_type'] = migval($record['traitor_type']);
            $event['special'] = migval($record['special']);
            $event['late_joiner'] = migval($record['late_joiner']);
            $event['success'] = migval($record['success']) === '1' ? true : false;
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting antag events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_antags')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End antags
        // Start antag objectives

        $this->info(PHP_EOL.PHP_EOL.'Parsing traitors_objectives.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/traitors_objectives.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        // $badObjectives = 0;
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $roundId = null;
            $playerId = null;
            if (array_key_exists('traitor'.$record['traitor_id'], $traitorRecords)) {
                $roundId = $traitorRecords['traitor'.$record['traitor_id']]['roundId'];
                $playerId = $traitorRecords['traitor'.$record['traitor_id']]['playerId'];
            }

            if (! $roundId) {
                // $badObjectives++;
                // $this->info('No round ID for antag objective. Legacy traitor ID: ' . $record['traitor_id'] .'. Player ID: ' . $playerId);
                continue;
            }

            $event = [];
            $event['round_id'] = $roundId ? $roundId : null;
            $event['player_id'] = $playerId ? $playerId : null;
            $event['objective'] = migval($record['objective']);
            $event['success'] = migval($record['success']) === '1' ? true : false;
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        // $this->info(PHP_EOL."Found $badObjectives bad objectives");

        $this->info(PHP_EOL.PHP_EOL.'Inserting antag objective events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_antag_objectives')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        // End antag objectives
        // Start antag item purchases

        $this->info(PHP_EOL.PHP_EOL.'Parsing traitor_items.csv and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/events/traitor_items.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $playerCkey = ckey($record['mob_byond_key']);
            $player = null;
            if (array_key_exists($playerCkey, $players)) {
                $player = $players[$playerCkey];
            }

            $event = [];
            $event['round_id'] = $record['round_id'];
            $event['player_id'] = $player ? $player['id'] : null;
            $event['mob_name'] = migval($record['mob_name']);
            $event['mob_job'] = migval($record['mob_job']);
            $event['x'] = migval($record['x']);
            $event['y'] = migval($record['y']);
            $event['z'] = migval($record['z']);
            $event['item'] = migval($record['item']);
            $event['cost'] = migval($record['cost']);
            $createdAt = $record['created_at'] === '0000-00-00 00:00:00' ? null : $record['created_at'];
            $event['created_at'] = $event['updated_at'] = $createdAt;
            $eventsToInsert[] = $event;
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting antag item purchase events'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($eventsToInsert) / 1000);
        $bar->start();
        foreach (array_chunk($eventsToInsert, 1000, true) as $key => $chunk) {
            DB::table('events_antag_item_purchases')->insert($chunk);
            $bar->advance();
        }
        $bar->finish();
        $eventsToInsert = [];

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
