<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\RemoteMusic;
use Illuminate\Http\Request;

/**
 * @tags Remote Music
 */
class RemoteMusicController extends Controller
{
    /**
     * Play
     *
     * Queue a piece of music from youtube to be played in a given round
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            /**
             * A full youtube video URL, or youtube video ID
             *
             * @example https://www.youtube.com/watch?v=dQw4w9WgXcQ
             */
            'video' => 'required',
            'round_id' => 'required|exists:game_rounds,id',
            'game_admin_ckey' => 'nullable|alpha_num',
        ]);

        $gameAdminCkey = isset($data['game_admin_ckey']) ? $data['game_admin_ckey'] : '';
        RemoteMusic::dispatch($data['video'], $data['round_id'], $gameAdminCkey);

        return ['message' => 'Success'];
    }
}
