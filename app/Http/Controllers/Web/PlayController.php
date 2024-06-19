<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GameServer;
use Illuminate\Http\Request;

class PlayController extends Controller
{
    // Dump temp thing to simulate a streamer server object just for this route
    private function getStreamerServer(string $serverId)
    {
        $server = null;
        if ($serverId === 'streamer1') {
            $server = [
                'name' => 'Goonstation Nightshade 1',
                'byond_link' => 'byond://tomato1.goonhub.com:27111',
            ];
        } elseif ($serverId === 'streamer2') {
            $server = [
                'name' => 'Goonstation Nightshade 2',
                'byond_link' => 'byond://tomato2.goonhub.com:27112',
            ];
        } elseif ($serverId === 'streamer3') {
            $server = [
                'name' => 'Goonstation Nightshade 3',
                'byond_link' => 'byond://tomato3.goonhub.com:27113',
            ];
        } elseif ($serverId === 'streamer4') {
            $server = [
                'name' => 'Goonstation Nightshade 4',
                'byond_link' => 'byond://tomato4.goonhub.com:27114',
            ];
        }

        return (object) $server;
    }

    public function index(Request $request, ?string $serverId = null)
    {
        $gameServer = null;
        if ($serverId) {
            if (in_array($serverId, ['streamer1', 'streamer2', 'streamer3', 'streamer4'])) {
                $gameServer = $this->getStreamerServer($serverId);
            } else {
                $gameServer = GameServer::where('server_id', $serverId)->where('active', true)->first();
            }
        }

        if (! $gameServer) {
            $gameServer = GameServer::where('active', true)->first();
        }

        return view('play', [
            'server' => $gameServer,
        ]);
    }
}
