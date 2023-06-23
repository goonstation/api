<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\PollOptionResource;
use App\Http\Resources\PollResource;
use App\Models\GameAdmin;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollOption;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class PollsController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<PollResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        $pollsPaged = $this->indexQuery(Poll::with(['gameAdmin', 'options']));
        $polls = $pollsPaged->getCollection();

        foreach ($polls as $poll) {
            $poll->options = $poll->options->sortBy('position');
        }

        $pollsPaged->setCollection($polls);
        return PollResource::collection($pollsPaged);
    }

    /**
     * Add
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'game_admin_ckey' => 'nullable|string|exists:game_admins,ckey',
            'question' => 'required',
            'expires_at' => 'nullable|date',
            'options' => 'required|array',
            'options.*' => 'sometimes|required'
        ]);

        $gameAdmin = null;
        if (isset($data['game_admin_ckey'])) {
            $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();
        }

        $poll = new Poll();
        $poll->game_admin_id = $gameAdmin ? $gameAdmin->id : null;
        $poll->question = $data['question'];
        $poll->expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $poll->save();

        foreach ($data['options'] as $key => $option) {
            $pollOption = new PollOption();
            $pollOption->option = $option;
            $pollOption->position = $key + 1;
            $poll->options()->save($pollOption);
        }

        return new PollResource($poll);
    }

    /**
     * Edit
     */
    public function update(Request $request, Poll $poll)
    {
        $data = $request->validate([
            'question' => 'required',
            'expires_at' => 'nullable|date'
        ]);

        $poll->question = $data['question'];
        $poll->expires_at = isset($data['expires_at']) ? $data['expires_at'] : null;
        $poll->save();

        return new PollResource($poll);
    }

    /**
     * Delete
     */
    public function destroy(Poll $poll)
    {
        $poll->delete();
        return ['message' => 'Poll removed'];
    }

    /**
     * Add option
     */
    public function addOption(Request $request, Poll $poll)
    {
        $data = $request->validate([
            'option' => 'required'
        ]);

        $currentOptionCount = PollOption::where('poll_id', $poll->id)->count();

        $pollOption = new PollOption();
        $pollOption->poll_id = $poll->id;
        $pollOption->option = $data['option'];
        $pollOption->position = $currentOptionCount + 1;
        $pollOption->save();

        return new PollOptionResource($pollOption);
    }

    /**
     * Update option
     */
    public function updateOption(Request $request, PollOption $pollOption)
    {
        $data = $request->validate([
            'option' => 'required',
            'position' => 'nullable|numeric'
        ]);

        $pollOption->option = $data['option'];
        $pollOption->position = isset($data['position']) && $data['position'] ? $data['position'] : $pollOption->position;
        $pollOption->save();

        return new PollOptionResource($pollOption);
    }

    /**
     * Delete option
     */
    public function destroyOption(PollOption $pollOption)
    {
        $pollOption->delete();
        return ['message' => 'Poll option removed'];
    }

    /**
     * Pick option
     */
    public function pickOption(Request $request, PollOption $pollOption)
    {
        $data = $request->validate([
            'player_id' => 'required|exists:players,id'
        ]);

        $existingAnswer = PollAnswer::where('player_id', $data['player_id'])
            ->whereRelation('option', 'poll_id', '=', $pollOption->poll->id)
            ->first();

        // Can't pick the same thing
        if ($existingAnswer->option->id === $pollOption->id) {
            return response()->json(['error' => 'You have already voted for that option.'], 400);
        }

        // Clear their previous pick
        if ($existingAnswer) {
            $existingAnswer->delete();
        }

        $answer = new PollAnswer();
        $answer->poll_option_id = $pollOption->id;
        $answer->player_id = $data['player_id'];
        $answer->save();

        return ['message' => $existingAnswer];
    }
}
