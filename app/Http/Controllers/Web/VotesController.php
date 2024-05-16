<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventDeath;
use App\Models\Events\EventFine;
use App\Models\Events\EventTicket;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VotesController extends Controller
{
    private $voteableTypes = [
        'death'  => EventDeath::class,
        'fine'   => EventFine::class,
        'ticket' => EventTicket::class,
    ];

    private function applyVote(string $direction, string $type, int $id)
    {
        $ip = request()->ip();
        $model = $this->voteableTypes[$type];
        $instance = $model::where('id', $id)->firstOrFail();

        $existingVote = Vote::where([
            'voteable_id' => $id,
            'voteable_type' => $model,
            'ip' => $ip,
        ])->first();

        if ($existingVote) {
            $existingVote->delete();

            // If user has already voted in this direction, just exit after removal
            if (
                ($direction === 'up' && $existingVote->value === 1) ||
                ($direction === 'down' && $existingVote->value === -1)
            ) {
                return $instance;
            }
        }

        $instance->votes()->create([
            'ip' => $ip,
            'value' => $direction === 'up' ? 1 : -1
        ]);

        return $instance;
    }

    public function upVote(Request $request)
    {
        $data = $this->validate($request, [
            'type' => ['required', Rule::in(array_keys($this->voteableTypes))],
            'id' => 'required|numeric',
        ]);

        $instance = $this->applyVote('up', $data['type'], $data['id']);
        return response()->json([
            'votes' => $instance->total_votes,
            'user_votes' => $instance->userVotes
        ]);
    }

    public function downVote(Request $request)
    {
        $data = $this->validate($request, [
            'type' => ['required', Rule::in(array_keys($this->voteableTypes))],
            'id' => 'required|numeric',
        ]);

        $instance = $this->applyVote('down', $data['type'], $data['id']);
        return response()->json([
            'votes' => $instance->total_votes,
            'user_votes' => $instance->userVotes
        ]);
    }
}
