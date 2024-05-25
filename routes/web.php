<?php

use App\Http\Controllers\Web\AntagsController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ChangelogController;
use App\Http\Controllers\Web\DeathsController;
use App\Http\Controllers\Web\EventsController;
use App\Http\Controllers\Web\FinesController;
use App\Http\Controllers\Web\GameAuthController;
use App\Http\Controllers\Web\GameServersController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MapsController;
use App\Http\Controllers\Web\PlayersController;
use App\Http\Controllers\Web\RedirectController;
use App\Http\Controllers\Web\RoundsController;
use App\Http\Controllers\Web\TerminalController;
use App\Http\Controllers\Web\TicketsController;
use App\Http\Controllers\Web\VotesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (! config('goonhub.include_frontend')) {
    return;
}

Route::controller(HomeController::class)->prefix('/')->group(function () {
    Route::get('/', 'index')->name('home')->breadcrumb('Home');
});

Route::controller(AuthController::class)->prefix('/auth')->group(function () {
    Route::get('/redirect', 'redirect')->name('auth.redirect');
    Route::get('/callback', 'callback')->name('auth.callback');
});

Route::controller(GameAuthController::class)->prefix('/game-auth')->group(function () {
    Route::get('/', 'show')->name('game-auth.show');
    Route::post('/', 'login')->name('game-auth.login');
});

Route::controller(ChangelogController::class)->prefix('/changelog')->group(function () {
    Route::get('/', 'index')->name('changelog')->breadcrumb('Changelog');
});

Route::controller(PlayersController::class)->prefix('/players')->group(function () {
    Route::get('/', 'index')->name('players.index')->breadcrumb('Players');
    Route::get('/search', 'search')->name('players.search')->breadcrumb('Search');
    Route::get('/{player}', 'show')->whereNumber('player')->name('players.show')->breadcrumb('', 'players.search');
});

Route::controller(RoundsController::class)->prefix('/rounds')->group(function () {
    Route::get('/', 'index')->name('rounds.index')->breadcrumb('Rounds');
    Route::get('/{round}', 'show')->whereNumber('round')->name('rounds.show')->breadcrumb('', 'rounds.index');
});

Route::prefix('/events')->group(function () {
    Route::controller(EventsController::class)->prefix('/')->group(function () {
        Route::get('/', 'index')->name('events.index')->breadcrumb('Events');
        Route::get('/stats', 'stats')->name('events.stats');
    });

    Route::controller(DeathsController::class)->prefix('/deaths')->group(function () {
        Route::get('/', 'index')->name('deaths.index')->breadcrumb('Deaths');
        Route::get('/{death}', 'show')->whereNumber('death')->name('deaths.show')->breadcrumb('', 'deaths.index');
    });

    Route::controller(TicketsController::class)->prefix('/tickets')->group(function () {
        Route::get('/', 'index')->name('tickets.index')->breadcrumb('Tickets');
        Route::get('/{ticket}', 'show')->whereNumber('ticket')->name('tickets.show')->breadcrumb('', 'tickets.index');
    });

    Route::controller(FinesController::class)->prefix('/fines')->group(function () {
        Route::get('/', 'index')->name('fines.index')->breadcrumb('Fines');
        Route::get('/{fine}', 'show')->whereNumber('fine')->name('fines.show')->breadcrumb('', 'fines.index');
    });

    Route::controller(AntagsController::class)->prefix('/antags')->group(function () {
        Route::get('/', 'index')->name('antags.index')->breadcrumb('Antagonists');
        Route::get('/{antag}', 'show')->whereNumber('antag')->name('antags.show')->breadcrumb('', 'antags.index');
    });
});

Route::controller(MapsController::class)->prefix('/maps')->group(function () {
    Route::get('/', 'index')->name('maps.index')->breadcrumb('Maps');
    Route::get('/{map}', 'show')->name('maps.show')->breadcrumb('', 'maps.index');
    Route::get('/private/{file}', 'getPrivateTile')->where('file', '.*')->name('maps.private');
});

Route::controller(RedirectController::class)->prefix('/r')->group(function () {
    Route::get('/{path}', 'redirect')->where('path', '.*')->name('redirect');
});

Route::controller(TerminalController::class)->prefix('/secret')->group(function () {
    Route::get('/', 'index')->name('terminal.index');
    Route::post('/login', 'login')->name('terminal.login');
    Route::post('/sudo', 'sudo')->name('terminal.sudo');
    Route::post('/print', 'print')->name('terminal.print');
});

Route::controller(GameServersController::class)->prefix('/game-servers')->group(function () {
    Route::get('/', 'index')->name('game-servers.index');
});

Route::controller(VotesController::class)->prefix('/votes')->group(function () {
    Route::post('/up', 'upVote')->name('votes.up');
    Route::post('/down', 'downVote')->name('votes.down');
});

require __DIR__.'/fortify.php';
require __DIR__.'/admin.php';
require __DIR__.'/jetstream.php';
