<?php

namespace App\Http\Controllers\Web;

use App\Facades\GameBridge;
use App\Http\Controllers\Controller;
use App\Models\GameServer;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;

class GameServersController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $data = $request->validate([
            'with_invisible' => 'nullable|boolean',
        ]);

        $query = GameServer::where('invisible', false);
        if (isset($data['with_invisible']) && $data['with_invisible'] && $request->user()?->game_admin_id) {
            $query = $query->orWhere('invisible', true);
        }

        $gameServers = $this->indexQuery(
            $query,
            perPage: 30,
            sortBy: 'name',
            desc: false
        );

        return $gameServers;
    }

    public function status(Request $request)
    {
        $request->validate([
            'server' => 'required|string',
        ]);

        $gameServer = GameServer::select(['server_id', 'address', 'port'])
            ->where('server_id', $request['server'])
            ->firstOrFail();

        $res = GameBridge::create()
            ->target($gameServer)
            ->message('status')
            ->send();

        if ($res->error) {
            return abort(500, $res->message);
        }

        parse_str($res->message, $status);

        return ['data' => $status];
    }
}
