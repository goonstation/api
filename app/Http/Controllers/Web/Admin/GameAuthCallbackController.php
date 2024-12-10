<?php

namespace App\Http\Controllers\Web\Admin;

use App\Facades\GameBridge;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameAuthCallbackController extends Controller
{
    public function informGame(Request $request)
    {
        $data = $request->validate([
            'server_id' => 'required',
        ]);

        $response = GameBridge::create()
            ->target($data['server_id'])
            ->message([
                'type' => 'goonhub_auth',
                'ckey' => $request->user()->gameAdmin->ckey,
            ])
            ->send();

        return response()->json([
            'success' => ! $response->error,
            'res' => $response->message,
        ]);
    }
}
