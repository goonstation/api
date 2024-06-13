<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameServer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $authFromGame = false;
        $authFromGameServer = null;
        if ($request->session()->has('auth_from_game')){
            $authFromGame = $request->session()->remove('auth_from_game');
            $authFromGameServer = GameServer::where('server_id', $authFromGame)->first();
        }

        return Inertia::render('Dashboard', [
            'authFromGame' => $authFromGame,
            'authFromGameServer' => $authFromGameServer,
        ]);
    }
}
