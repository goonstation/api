<?php

namespace App\Console\Commands;

use App\Facades\GameBridge;
use App\Models\GameServer;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $server = GameServer::find(6);
        $bridge = GameBridge::create()
            ->target(['dev', 'main1'])
            // ->force(true)
            ->message('status');
        $response = $bridge->send();
        dump($response);

        // $bridge = GameBridge::create()
        //     ->target('dev')
        //     ->force(true)
        //     ->message('ping');
        // $bridge->sendAndForget();
        return 0;
    }
}
