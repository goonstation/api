<?php

use App\Http\Controllers\Web\Admin\BansController as AdminBansController;
use App\Http\Controllers\Web\Admin\PlayersController as AdminPlayersController;
use App\Http\Controllers\Web\ChangelogController;
use App\Http\Controllers\Web\DeathsController;
use App\Http\Controllers\Web\EventsController;
use App\Http\Controllers\Web\FinesController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MapsController;
use App\Http\Controllers\Web\PlayersController;
use App\Http\Controllers\Web\RoundsController;
use App\Http\Controllers\Web\TicketsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

if (!env('INCLUDE_FRONTEND')) return;

Route::get('/test', function () {
    return Inertia::render('Test');
})->name('test');

Route::get('/login', function () {
    return Inertia::render('Jetstream/Auth/Login');
})->name('login');

Route::controller(HomeController::class)->prefix('/')->group(function () {
    Route::get('/', 'index')->name('home')->breadcrumb('Home');
});

Route::controller(ChangelogController::class)->prefix('/changelog')->group(function () {
    Route::get('/', 'index')->name('changelog')->breadcrumb('Changelog');
});

Route::controller(PlayersController::class)->prefix('/players')->group(function () {
    Route::get('/', 'index')->name('players.index')->breadcrumb('Players');
    Route::get('/highscores', 'highscores')->name('players.highscores')->breadcrumb('Highscores');
    Route::get('/search', 'search')->name('players.search')->breadcrumb('Search');
    Route::get('/{player}', 'show')->name('players.show')->breadcrumb('', 'players.search');
});

Route::controller(RoundsController::class)->prefix('/rounds')->group(function () {
    Route::get('/', 'index')->name('rounds.index')->breadcrumb('Rounds');
    Route::get('/{round}', 'show')->name('rounds.show')->breadcrumb('', 'rounds.index');
});

Route::prefix('/events')->group(function () {
    Route::controller(EventsController::class)->prefix('/')->group(function () {
        Route::get('/', 'index')->name('events.index')->breadcrumb('Events');
    });

    Route::controller(DeathsController::class)->prefix('/deaths')->group(function () {
        Route::get('/', 'index')->name('deaths.index')->breadcrumb('Deaths');
        Route::get('/{death}', 'show')->name('deaths.show')->breadcrumb('', 'deaths.index');
    });

    Route::controller(TicketsController::class)->prefix('/tickets')->group(function () {
        Route::get('/', 'index')->name('tickets.index')->breadcrumb('Tickets');
        Route::get('/{ticket}', 'show')->name('tickets.show')->breadcrumb('', 'tickets.index');
    });

    Route::controller(FinesController::class)->prefix('/fines')->group(function () {
        Route::get('/', 'index')->name('fines.index')->breadcrumb('Fines');
        Route::get('/{fine}', 'show')->name('fines.show')->breadcrumb('', 'fines.index');
    });
});

Route::controller(MapsController::class)->prefix('/maps')->group(function () {
    Route::get('/', 'index')->name('maps.index')->breadcrumb('Maps');
    Route::get('/{map}', 'show')->name('maps.show')->breadcrumb('', 'maps.index');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');

//     Route::controller(AdminPlayersController::class)->prefix('players')->group(function () {
//         Route::get('/', 'index')->name('players.index');
//     });

//     Route::controller(AdminBansController::class)->prefix('bans')->group(function () {
//         Route::get('/', 'index')->name('bans.index');
//         Route::get('/details', 'getDetails');
//     });
// });
