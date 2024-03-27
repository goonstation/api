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
        $maps = Map::select('id', 'map_id', 'name', 'last_built_at')
            ->with([
                'latestGameRound' => function ($q) {
                    $q->where('ended_at', '!=', null)
                        ->whereRelation('server', 'invisible', false);
                },
            ])
            ->where('active', true)
            ->where('is_layer', false)
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Maps/Index', [
            'maps' => $maps,
        ]);
    }

    public function show(string $map)
    {
        $map = Map::select('id', 'map_id', 'name', 'tile_width', 'tile_height', 'screenshot_tiles', 'updated_at')
            ->where('map_id', Str::upper($map))
            ->where('active', true)
            ->where('is_layer', false)
            ->with('layers')
            ->firstOrFail();

        return Inertia::render('Maps/Show', [
            'map' => $map,
        ]);
    }
}
