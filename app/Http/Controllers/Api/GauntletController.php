<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events\EventGauntletHighScore;
use Illuminate\Http\Request;

class GauntletController extends Controller
{
    /**
     * Get previous
     *
     * Retrieve a count of how many gauntlets a given key has completed
     */
    public function getPrevious(Request $request)
    {
        $data = $this->validate($request, [
            'key' => 'required|string',
        ]);

        $gauntletsCompleted = EventGauntletHighScore::where('names', 'LIKE', '%'.$data['key'].'%')
            ->where('highest_wave', '>', 0)
            ->count();

        return ['data' => [
            /** @var int */
            'gauntlets_completed' => $gauntletsCompleted,
        ]];
    }
}
