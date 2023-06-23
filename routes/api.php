<?php

use App\Http\Controllers\Api\BansController;
use App\Http\Controllers\Api\GameRoundsController;
use App\Http\Controllers\Api\JobBansController;
use App\Http\Controllers\Api\MapsController;
use App\Http\Controllers\Api\MapSwitchesController;
use App\Http\Controllers\Api\PlayerAntagsController;
use App\Http\Controllers\Api\PlayerMetadataController;
use App\Http\Controllers\Api\PlayerNotesController;
use App\Http\Controllers\Api\PlayerParticipationsController;
use App\Http\Controllers\Api\PlayerPlaytimeController;
use App\Http\Controllers\Api\PlayerSavesController;
use App\Http\Controllers\Api\PlayersController;
use App\Http\Controllers\Api\PollsController;
use App\Http\Controllers\Api\RandomEntriesController;
use App\Http\Controllers\Api\RemoteMusicController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\VpnChecksController;
use App\Http\Controllers\Api\VpnWhitelistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('test', [TestController::class, 'index']);

    Route::controller(GameRoundsController::class)->prefix('rounds')->group(function () {
        Route::post('/', 'store');
        Route::put('/{gameRound}', 'update');
        Route::put('/end/{gameRound}', 'endRound');
    });
    Route::controller(PlayersController::class)->prefix('players')->group(function () {
        Route::post('/', 'store');
        Route::get('/search', 'search');
        Route::get('/stats', 'stats');
    });
    Route::controller(PlayerParticipationsController::class)->prefix('players/participations')->group(function () {
        Route::post('/', 'store');
    });
    Route::controller(PlayerPlaytimeController::class)->prefix('players/playtime')->group(function () {
        Route::post('/bulk', 'storeBulk');
    });
    Route::controller(PlayerAntagsController::class)->prefix('players/antags')->group(function () {
        Route::post('/', 'store');
    });
    Route::controller(PlayerNotesController::class)->prefix('players/notes')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{note}', 'update');
        Route::delete('/{note}', 'destroy');
    });
    Route::controller(PlayerSavesController::class)->prefix('players/saves')->group(function () {
        Route::get('/', 'index');
        Route::post('/data', 'storeData');
        Route::post('/file', 'storeFile');
        Route::post('/data-bulk', 'storeDataBulk');
        Route::delete('/data/{playerData}', 'destroyData');
        Route::delete('/file/{playerSave}', 'destroyFile');
    });
    Route::controller(PlayerMetadataController::class)->prefix('players/metadata')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('/clear-by-player/{ckey}', 'destroyByPlayer');
        Route::delete('/clear-by-data/{data}', 'destroyByData');
    });
    Route::controller(BansController::class)->prefix('bans')->group(function () {
        Route::get('/', 'index');
        Route::get('/check', 'check');
        Route::post('/', 'store');
        Route::put('/{ban}', 'update');
        Route::delete('/{ban}', 'destroy');
        Route::post('/details/{ban}', 'addDetails');
        Route::delete('/details/{banDetail}', 'destroyDetail');
    });
    Route::controller(JobBansController::class)->prefix('job-bans')->group(function () {
        Route::get('/', 'index');
        Route::get('/check', 'check');
        Route::get('/get-for-player', 'getForPlayer');
        Route::post('/', 'store');
        Route::put('/{jobBan}', 'update');
        Route::delete('/{jobBan}', 'destroy');
    });
    Route::controller(MapsController::class)->prefix('maps')->group(function () {
        Route::post('/build', 'build');
    });
    Route::controller(MapSwitchesController::class)->prefix('map-switch')->group(function () {
        Route::post('/', 'store');
    });
    Route::controller(VpnChecksController::class)->prefix('vpncheck')->group(function () {
        Route::get('/{ip}', 'check');
    });
    Route::controller(VpnWhitelistController::class)->prefix('vpncheck-whitelist')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::delete('/{vpnWhitelist}', 'destroy');
    });
    Route::controller(RemoteMusicController::class)->prefix('remote-music')->group(function () {
        Route::post('/', 'store');
    });
    Route::controller(RandomEntriesController::class)->prefix('random-entries')->group(function () {
        Route::get('/', 'index');
    });
    Route::controller(PollsController::class)->prefix('polls')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{poll}', 'update');
        Route::delete('/{poll}', 'destroy');
        Route::post('/option/{poll}', 'addOption');
        Route::put('/option/{pollOption}', 'updateOption');
        Route::delete('/option/{pollOption}', 'destroyOption');
        Route::post('/option/pick/{pollOption}', 'pickOption');
    });
});
