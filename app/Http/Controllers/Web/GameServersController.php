<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GameServer;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;

class GameServersController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $gameServers = $this->indexQuery(
            GameServer::where('active', true)->where('invisible', false),
            perPage: 30,
            sortBy: 'name',
            desc: false
        );

        return $gameServers;
    }
}
