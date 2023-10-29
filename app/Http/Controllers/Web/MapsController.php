<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Inertia\Inertia;
use Illuminate\Support\Str;

class MapsController extends Controller
{
    public function index()
    {
        $maps = Map::with([
                'latestGameRound' => function($q) {
                    $q->where('ended_at', '!=', null)
                        ->whereRelation('server', 'invisible', false);
                }
            ])
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Maps/Index', [
            'maps' => $maps,
        ]);
    }

    public function show(string $map)
    {
        $map = Map::where('map_id', Str::upper($map))->firstOrFail();

        return Inertia::render('Maps/Show', [
            'map' => Str::lower($map->map_id),
        ]);
    }
}
