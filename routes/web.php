<?php

use App\Http\Controllers\Web\Admin\BansController as AdminBansController;
use App\Http\Controllers\Web\Admin\GameAdminRanksController as AdminGameAdminRanksController;
use App\Http\Controllers\Web\Admin\GameAdminsController as AdminGameAdminsController;
use App\Http\Controllers\Web\Admin\MapsController as AdminMapsController;
use App\Http\Controllers\Web\Admin\PlayersController as AdminPlayersController;
use App\Http\Controllers\Web\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Web\AntagsController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ChangelogController;
use App\Http\Controllers\Web\DeathsController;
use App\Http\Controllers\Web\DonateController;
use App\Http\Controllers\Web\EventsController;
use App\Http\Controllers\Web\FinesController;
use App\Http\Controllers\Web\GameServersController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MapsController;
use App\Http\Controllers\Web\PlayersController;
use App\Http\Controllers\Web\RedirectController;
use App\Http\Controllers\Web\RoundsController;
use App\Http\Controllers\Web\TerminalController;
use App\Http\Controllers\Web\TicketsController;
use App\Http\Middleware\EnsureUserIsGameAdmin;
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

Route::controller(ChangelogController::class)->prefix('/changelog')->group(function () {
    Route::get('/', 'index')->name('changelog')->breadcrumb('Changelog');
});

Route::controller(PlayersController::class)->prefix('/players')->group(function () {
    Route::get('/', 'index')->name('players.index')->breadcrumb('Players');
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
        Route::get('/stats', 'stats')->name('events.stats');
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

    Route::controller(AntagsController::class)->prefix('/antags')->group(function () {
        Route::get('/', 'index')->name('antags.index')->breadcrumb('Antagonists');
        Route::get('/{antag}', 'show')->name('antags.show')->breadcrumb('', 'antags.index');
    });
});

Route::controller(MapsController::class)->prefix('/maps')->group(function () {
    Route::get('/', 'index')->name('maps.index')->breadcrumb('Maps');
    Route::get('/{map}', 'show')->name('maps.show')->breadcrumb('', 'maps.index');
});

Route::controller(DonateController::class)->prefix('/donate')->group(function () {
    Route::get('/', 'index')->name('donate.index');
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/admin')->middleware([EnsureUserIsGameAdmin::class])->group(function () {
        Route::controller(AdminUsersController::class)->prefix('users')->group(function () {
            Route::get('/', 'index')->name('admin.users.index');
        });

        Route::controller(AdminGameAdminRanksController::class)->prefix('game-admin-ranks')->group(function () {
            Route::get('/', 'index')->name('admin.game-admin-ranks.index')->breadcrumb('Admin Ranks');
            Route::get('/create', 'create')->name('admin.game-admin-ranks.create')->breadcrumb('', 'admin.game-admin-ranks.index');
            Route::post('/', 'store')->name('admin.game-admin-ranks.store');
        });

        Route::controller(AdminGameAdminsController::class)->prefix('game-admins')->group(function () {
            Route::get('/', 'index')->name('admin.game-admins.index')->breadcrumb('Admins');
            Route::get('/{gameAdmin}', 'show')->name('admin.game-admins.show')->breadcrumb('', 'admin.game-admins.index');
        });

        Route::controller(AdminPlayersController::class)->prefix('players')->group(function () {
            Route::get('/', 'index')->name('admin.players.index')->breadcrumb('Players');
            Route::get('/{player}', 'show')->name('admin.players.show')->breadcrumb('', 'admin.players.index');
        });

        Route::controller(AdminBansController::class)->prefix('bans')->group(function () {
            Route::get('/', 'index')->name('admin.bans.index')->breadcrumb('Bans');
            Route::get('/removed', 'indexRemoved')->name('admin.bans.index-removed')->breadcrumb('Bans');
            Route::get('/details', 'getDetails');
            Route::get('/create', 'create')->name('admin.bans.create')->breadcrumb('', 'admin.bans.index');
            Route::post('/', 'store')->name('admin.bans.store');
            Route::get('/edit/{ban}', 'edit')->name('admin.bans.edit')->breadcrumb('', 'admin.bans.index');
            Route::put('/{ban}', 'update')->name('admin.bans.update');
        });

        Route::controller(AdminMapsController::class)->prefix('maps')->group(function () {
            Route::get('/', 'index')->name('admin.maps.index')->breadcrumb('Maps');
            Route::get('/upload', 'showUpload')->name('admin.maps.upload')->breadcrumb('', 'admin.maps.index');
            Route::post('/upload', 'upload')->name('admin.maps.update');
            Route::post('/upload-file', 'uploadFile')->name('admin.maps.upload-file');
            Route::get('/create', 'create')->name('admin.maps.create')->breadcrumb('', 'admin.maps.index');
            Route::post('/', 'store')->name('admin.maps.store');
            Route::get('/edit/{map}', 'edit')->name('admin.maps.edit')->breadcrumb('', 'admin.maps.index');
            Route::put('/{map}', 'update')->name('admin.maps.update');
        });
    });
});

require_once __DIR__.'/jetstream.php';
