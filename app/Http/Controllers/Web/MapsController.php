<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Map;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MapsController extends Controller
{
    private $maps = [
        'atlas' => ['name' => 'Atlas'],
        'cogmap' => ['name' => 'Cogmap'],
        'cogmap2' => ['name' => 'Cogmap 2'],
        'clarion' => ['name' => 'Clarion'],
        'destiny' => ['name' => 'Destiny'],
        'donut2' => ['name' => 'Donut 2'],
        'donut3' => ['name' => 'Donut 3'],
        'kondaru' => ['name' => 'Kondaru'],
        'nadir' => ['name' => 'Nadir'],
        'oshan' => ['name' => 'Oshan'],
        'podwars' => ['name' => 'Pod Wars'],
    ];

    public function index(Request $request)
    {
        return Inertia::render('Maps/Index', [
            'maps' => Map::orderBy('name', 'asc')->get(),
        ]);
    }

    public function show(Request $request, string $map)
    {
        if (! array_key_exists($map, $this->maps)) {
            return abort(404);
        }

        return Inertia::render('Maps/Show', [
            'map' => $map,
        ]);
    }
}
