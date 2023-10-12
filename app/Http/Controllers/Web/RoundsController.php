<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GameRound;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoundsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $rounds = $this->indexQuery(GameRound::with([
            'server:server_id,name',
            'latestStationName',
        ])->where('ended_at', '!=', null), perPage: 30);

        if ($this->wantsInertia()) {
            return Inertia::render('Rounds/Index', [
                'rounds' => $rounds,
            ]);
        }

        return $rounds;
    }

    public function show(Request $request, int $round)
    {
        $gameRound = GameRound::with([
            'server:server_id,name',
            'latestStationName:id,round_id,name',
            'aiLaws:id,round_id,ai_name,law_number,law_text,uploader_name',
            'deaths:id,round_id,mob_name,mob_job,bruteloss,fireloss,toxloss,oxyloss,gibbed,created_at',
            'fines:id,round_id,issuer,target,reason,amount',
            'tickets:id,round_id,issuer,target,reason',
            'antags:id,round_id,player_id,mob_name,mob_job,traitor_type,success',
            'antagObjectives:id,round_id,player_id,objective,success',
            'antagItemPurchases',
            'participations:round_id,created_at',
        ])->withCount([
            'beeSpawns',
        ])
            ->where('ended_at', '!=', null)
            ->findOrFail($round);

        return Inertia::render('Rounds/Show', [
            'round' => $gameRound,
        ]);
    }
}
