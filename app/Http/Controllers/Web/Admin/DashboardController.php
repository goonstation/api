<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameServer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Health\Facades\Health;

class DashboardController extends Controller
{
    private function getHealth()
    {
        $stores = Health::resultStores();
        /** @var \Spatie\Health\ResultStores\EloquentHealthResultStore */
        $store = $stores->first();
        $checkResults = $store->latestResults();

        return $checkResults ?? [];
    }

    private function getAuthFromGame(Request $request)
    {
        if ($request->session()->has('auth_from_game')) {
            $authFromGame = $request->session()->remove('auth_from_game');

            return GameServer::where('server_id', $authFromGame)->first();
        }
    }

    public function index(Request $request)
    {
        return Inertia::render('Dashboard/Index', [
            'authFromGame' => fn () => $this->getAuthFromGame($request),
            'health' => fn () => $this->getHealth(),
        ]);
    }
}
