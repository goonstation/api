<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlayerNote;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayerNotesController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $playerNotes = $this->indexQuery(
          PlayerNote::with([
            'player:id,ckey',
            'gameAdmin:id,name,ckey',
            'gameServer:id,server_id,short_name'
          ]), 
          perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/PlayerNotes/Index', [
                'playerNotes' => $playerNotes,
            ]);
        } else {
            return $playerNotes;
        }
    }
}
