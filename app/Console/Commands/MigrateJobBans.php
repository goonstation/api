<?php

namespace App\Console\Commands;

use App\Models\GameAdmin;
use App\Models\JobBan;
use Illuminate\Console\Command;
use League\Csv\Reader;

class MigrateJobBans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:jobbans';

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

        $adminCkey = strtolower($adminCkey);
        $adminCkey = preg_replace('/[^a-z\d]/i', '', $adminCkey);

        return $adminCkey;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Parsing CSV and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/jobbans.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();

        $gameAdmins = GameAdmin::all();

        foreach ($records as $offset => $record) {
            $adminCkey = $this->ckeyIzeAdmin($record['admin_ckey']);
            $gameAdmin = $gameAdmins->where('ckey', $adminCkey)->first();

            $jobBan = new JobBan();
            $jobBan->timestamps = false;
            $jobBan->game_admin_id = $gameAdmin->id;
            $jobBan->server_id = $record['server_id'];
            $jobBan->ckey = $this->ckeyIzeAdmin($record['ckey']);
            $jobBan->banned_from_job = $record['banned_from_job'];
            $jobBan->created_at = $record['created_at'];
            $jobBan->deleted_at = $record['deleted_at'] === "\N" ? null : $record['deleted_at'];
            $jobBan->save();

            $bar->advance();
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
