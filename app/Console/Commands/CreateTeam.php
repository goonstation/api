<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;

class CreateTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-team';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a team';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $teamName = $this->ask('Name?');
        $teamOwner = $this->ask('Team owner user ID?');

        if (! $teamName || ! $teamOwner) {
            $this->error('Not enough details.');

            return Command::FAILURE;
        }

        $team = new Team();
        $team->user_id = $teamOwner;
        $team->name = $teamName;
        $team->personal_team = false;
        $team->save();

        $this->info('Team created!');

        return Command::SUCCESS;
    }
}
