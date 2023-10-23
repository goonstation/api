<?php

namespace App\Console\Commands;

use App\Models\GameAdmin;
use App\Models\GameAdminRank;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use League\Csv\Reader;

class MigrateGameAdmins extends Command
{
    use HasGoonTeamHandling;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:gameadmins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function getAdminsTxt()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/goonstation/goonstation-secret/contents/config/admins.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = [];
        $headers[] = 'Accept: application/vnd.github+json';
        $headers[] = 'Authorization: Bearer '.config('github.user_token');
        $headers[] = 'X-Github-Api-Version: 2022-11-28';
        $headers[] = 'User-Agent: SomethingOrOther';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:'.curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result);
        $result = base64_decode($result->content);
        $lines = preg_split('/\r\n|\n|\r/', trim($result));
        $admins = [];
        foreach ($lines as $line) {
            if (! $line || str_starts_with($line, '#')) {
                continue;
            }
            $details = explode('-', $line);
            $admins[trim($details[0])] = trim($details[1]);
        }

        return $admins;
    }

    private function ckeyIzeAdmin($adminCkey)
    {
        if (str_ends_with($adminCkey, '(Discord)')) {
            $adminCkey = str_replace(' (Discord)', '', $adminCkey);
        }
        if (str_ends_with($adminCkey, '(AUTO)')) {
            $adminCkey = str_replace(' (AUTO)', '', $adminCkey);
        }

        return ckey($adminCkey);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Parsing admins.txt from GitHub'.PHP_EOL);
        $admins = $this->getAdminsTxt();

        foreach ($admins as $admin => $rank) {
            $admins[$admin] = ['rank' => $rank];
        }

        $this->info('Parsing admin team from Google sheets'.PHP_EOL);
        $this->getAdminTeamSheet();
        $adminsFromSheet = $this->getTeamAdminsByRank();
        foreach ($adminsFromSheet as $admin => $details) {
            if (array_key_exists($admin, $admins)) {
                $admins[$admin] = $details;
            }
        }

        $this->info('Parsing bans CSV and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/bans.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $adminCkey = $record['original_admin_ckey'];
            if ($adminCkey === 'N/A') {
                $adminCkey = $record['admin_ckey'];
            }

            $isExternalAdmin = str_ends_with($adminCkey, '(Discord)') || str_ends_with($adminCkey, '(AUTO)');
            $adminCkey = $this->ckeyIzeAdmin($adminCkey);
            if (! $isExternalAdmin && ! array_key_exists($adminCkey, $admins)) {
                $admins[$adminCkey] = ['rank' => 'Inactive'];
            }
            $bar->advance();
        }
        $bar->finish();

        // $this->info(PHP_EOL.PHP_EOL.'Parsing notes CSV and processing data'.PHP_EOL);
        // $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/notes.csv', 'r');
        // $reader->setHeaderOffset(0);
        // $records = $reader->getRecords();
        // $bar = $this->output->createProgressBar(count($reader));
        // $bar->start();
        // foreach ($records as $offset => $record) {
        //     $adminCkey = $this->ckeyIzeAdmin($record['admin_ckey']);
        //     if (!array_key_exists($adminCkey, $admins)) $admins[$adminCkey] = '';
        //     $bar->advance();
        // }
        // $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Parsing jobbans CSV and processing data'.PHP_EOL);
        $reader = Reader::createFromPath(__DIR__.'/old-goonhub-data/jobbans.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $bar = $this->output->createProgressBar(count($reader));
        $bar->start();
        foreach ($records as $offset => $record) {
            $isExternalAdmin = str_ends_with($adminCkey, '(Discord)') || str_ends_with($adminCkey, '(AUTO)');
            $adminCkey = $this->ckeyIzeAdmin($record['admin_ckey']);
            if (! $isExternalAdmin && ! array_key_exists($adminCkey, $admins)) {
                $admins[$adminCkey] = ['rank' => 'Inactive'];
            }
            $bar->advance();
        }
        $bar->finish();

        $this->info(PHP_EOL.PHP_EOL.'Inserting data'.PHP_EOL);
        $bar = $this->output->createProgressBar(count($admins));
        $bar->start();
        $botAdmins = [
            'autobanner',
            'nerdbanner',
            'autotagger',
            'fpjs',
            'auto',
            'vpnblocker',
        ];
        $notAdmins = ['omjenkins'];
        GameAdminRank::insert([
            ['rank' => 'Host'],
            ['rank' => 'Coder'],
            ['rank' => 'Administrator'],
            ['rank' => 'Inactive'],
            ['rank' => 'Bot'],
        ]);
        $botAdminRank = GameAdminRank::where('rank', 'Bot')->first();
        $gameAdmin = new GameAdmin();
        $gameAdmin->ckey = 'bot';
        $gameAdmin->name = 'Bot';
        $gameAdmin->rank_id = $botAdminRank->id;
        $gameAdmin->save();
        foreach ($admins as $admin => $details) {
            $bar->advance();
            if (empty($admin) || in_array($admin, $notAdmins)) {
                continue;
            }
            if (in_array($admin, $botAdmins)) {
                continue;
            }

            $rank = $details['rank'];
            $rankModel = null;
            if ($rank) {
                $rankModel = GameAdminRank::where('rank', $rank)->first();
            }

            $discordId = null;
            if (array_key_exists('discord_id', $details)) {
                $discordId = $details['discord_id'];
            }

            $newAdmin = new GameAdmin();
            $newAdmin->ckey = $admin;
            if ($rankModel && $rankModel->id) {
                $newAdmin->rank_id = $rankModel->id;
            }
            $newAdmin->save();

            $userName = $admin;
            if (array_key_exists('alias', $details)) {
                $userName = $details['alias'];
            }

            if ($rank !== 'Bot' && $rank !== 'Inactive') {
                $user = new User();
                $user->name = $userName;
                $user->email = Str::random(20).'@goonhub.com';
                $user->password = Hash::make(Str::random(30));
                $user->discord_id = $discordId ? $discordId : null;
                $user->game_admin_id = $newAdmin->id;
                $user->save();
            }
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
