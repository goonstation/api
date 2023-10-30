<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MapsController extends Controller
{
    public function index()
    {
        $maps = Map::with([
            'latestGameRound' => function ($q) {
                $q->where('ended_at', '!=', null)
                    ->whereRelation('server', 'invisible', false);
            },
        ])
            ->where('active', true)
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Maps/Index', [
            'maps' => $maps,
        ]);
    }

    public function show(string $map)
    {
        $map = Map::where('map_id', Str::upper($map))->where('active', true)->firstOrFail();

        return Inertia::render('Maps/Show', [
            'map' => Str::lower($map->map_id),
            'mapName' => $map->name,
        ]);
    }
}
