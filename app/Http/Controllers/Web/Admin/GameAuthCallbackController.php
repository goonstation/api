<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\GameBridge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameAuthCallbackController extends Controller
{
    public function informGame(Request $request)
    {
        $data = $request->validate([
            'server_id' => 'required',
        ]);

        $reachedGame = false;
        $res = null;
        try {
            $res = GameBridge::relay($data['server_id'], [
                'type' => 'goonhub_auth',
                'ckey' => Auth::user()->gameAdmin->ckey,
            ]);
            $reachedGame = true;
        } catch (\Throwable $e) {
            // pass
        }

        return response()->json([
            'success' => $reachedGame,
            'res' => $res,
        ]);
    }
}
