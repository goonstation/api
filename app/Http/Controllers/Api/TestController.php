<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\GamePlayMode;
use Spatie\SchemaOrg\Schema;

class TestController extends Controller
{
    /**
     * Test
     */
    public function index(Request $request)
    {
        $author = Schema::organization()
            ->name('Goonstation')
            ->url('https://github.com/goonstation/goonstation');

        $playMode = Schema::gamePlayMode()
            ->name(GamePlayMode::MultiPlayer);

        $numberOfPlayers = Schema::quantitativeValue()
            ->value('Number')
            ->minValue(0)
            ->maxValue(200);

        $language = Schema::language()
            ->name('English')
            ->alternateName('en');

        $game = Schema::videoGame()
            ->name('Goonstation')
            ->description('')
            ->author($author)
            ->genre([''])
            ->gamePlatform(['PC game'])
            ->playMode($playMode)
            ->numberOfPlayers($numberOfPlayers)
            ->downloadUrl('https://www.byond.com/download/')
            ->inLanguage($language);

        return response()->json([
            'message' => $game,
        ]);
    }
}
