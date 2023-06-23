<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\RemoteMusic;
use Illuminate\Http\Request;

class RemoteMusicController extends Controller
{
    /**
     * Play
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'video' => 'required',
            'round_id' => 'required|exists:game_rounds,id',
        ]);

        RemoteMusic::dispatch($data['video'], $data['round_id']);

        return ['message' => 'Success'];
    }
}
