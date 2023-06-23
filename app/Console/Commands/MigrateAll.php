<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:all';

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
        $this->call('ghmigrate:gameadmins');
        $this->call('ghmigrate:gamerounds');
        $this->call('ghmigrate:players');
        $this->call('ghmigrate:playerslegacy');
        $this->call('ghmigrate:antags');
        $this->call('ghmigrate:bans');
        $this->call('ghmigrate:jobbans');
        $this->call('ghmigrate:notes');
        $this->call('ghmigrate:mapswitches');
        $this->call('ghmigrate:vpnchecks');
        $this->call('ghmigrate:metadata');
        $this->call('ghmigrate:clouddata');
        $this->call('ghmigrate:events');

        return Command::SUCCESS;
    }
}
