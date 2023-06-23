<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventFine;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinesController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $fines = $this->indexQuery(EventFine::class, perPage: 20);

        if ($this->wantsInertia()) {
            return Inertia::render('Events/Fines/Index', [
                'fines' => $fines,
            ]);
        }

        return $fines;
    }

    public function show(Request $request, EventFine $fine)
    {
        return Inertia::render('Events/Fines/Show', [
            'fine' => $fine,
        ]);
    }
}
