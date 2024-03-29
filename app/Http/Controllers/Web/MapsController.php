<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->orderBy('name', 'asc');

        $user = Auth::user();
        if (!$user || !$user->game_admin_id) {
            $maps = $maps->where('admin_only', false);
        }

        return Inertia::render('Maps/Index', [
            'maps' => $maps->get(),
        ]);
    }

    public function show(string $map)
    {
        $user = Auth::user();
        $map = Map::select('id', 'map_id', 'name', 'tile_width', 'tile_height', 'screenshot_tiles', 'updated_at')
            ->where('map_id', Str::upper($map))
            ->where('active', true)
            ->where('is_layer', false)
            ->with([
                'layers' => function ($q) use ($user) {
                    if (!$user || !$user->game_admin_id) {
                        $q->where('admin_only', false);
                    }
                }
            ]);

        if (!$user || !$user->game_admin_id) {
            $map = $map->where('admin_only', false);
        }

        $map = $map->firstOrFail();
        return Inertia::render('Maps/Show', [
            'map' => $map,
        ]);
    }

    public function getPrivateTile(Request $request, string $path)
    {
        $user = Auth::user();
        if (!$user || !$user->game_admin_id) {
            return abort(404);
        }

        $file = storage_path('app/private-maps/' . $path);
        return response()->download($file);
    }
}
