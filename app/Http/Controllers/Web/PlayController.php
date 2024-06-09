<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GameServer;
use Illuminate\Http\Request;

class PlayController extends Controller
{
    public function index(Request $request, string $serverId = null)
    {
        $gameServer = null;
        if ($serverId) {
            $gameServer = GameServer::where('server_id', $serverId)->where('active', true)->first();
        }

        if (!$gameServer) {
            $gameServer = GameServer::where('active', true)->first();
        }

        return view('play', [
            'server' => $gameServer
        ]);
    }
}
