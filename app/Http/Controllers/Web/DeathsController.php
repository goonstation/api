<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventDeath;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeathsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $deaths = $this->indexQuery(EventDeath::class, perPage: 20);

        if ($this->wantsInertia()) {
            return Inertia::render('Events/Deaths/Index', [
                'deaths' => $deaths,
            ]);
        }

        return $deaths;
    }

    public function show(Request $request, EventDeath $death)
    {
        return Inertia::render('Events/Deaths/Show', [
            'death' => $death,
        ]);
    }
}
