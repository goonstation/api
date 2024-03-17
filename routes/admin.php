<?php

use App\Http\Controllers\Web\Admin\BansController;
use App\Http\Controllers\Web\Admin\EventsController;
use App\Http\Controllers\Web\Admin\GameAdminRanksController;
use App\Http\Controllers\Web\Admin\GameAdminsController;
use App\Http\Controllers\Web\Admin\LogsController;
use App\Http\Controllers\Web\Admin\MapsController;
use App\Http\Controllers\Web\Admin\PlayerNotesController;
use App\Http\Controllers\Web\Admin\PlayersController;
use App\Http\Controllers\Web\Admin\UsersController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsGameAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/admin')->middleware([EnsureUserIsGameAdmin::class])->group(function () {
        Route::controller(UsersController::class)->prefix('users')->middleware([EnsureUserIsAdmin::class])->group(function () {
            Route::get('/', 'index')->name('admin.users.index')->breadcrumb('Users');
            Route::get('/create', 'create')->name('admin.users.create')->breadcrumb('', 'admin.users.index');
            Route::post('/', 'store')->name('admin.users.store');
            Route::get('/edit/{user}', 'edit')->name('admin.users.edit')->breadcrumb('', 'admin.users.index');
            Route::put('/{user}', 'update')->whereNumber('user')->name('admin.users.update');
        });

        Route::controller(GameAdminRanksController::class)->prefix('game-admin-ranks')->group(function () {
            Route::get('/', 'index')->name('admin.game-admin-ranks.index')->breadcrumb('Admin Ranks');
            Route::get('/create', 'create')->name('admin.game-admin-ranks.create')->breadcrumb('', 'admin.game-admin-ranks.index');
            Route::post('/', 'store')->name('admin.game-admin-ranks.store');
        });

        Route::controller(GameAdminsController::class)->prefix('game-admins')->group(function () {
            Route::get('/', 'index')->name('admin.game-admins.index')->breadcrumb('Admins');
            Route::get('/{gameAdmin}', 'show')
                ->whereNumber('gameAdmin')
                ->name('admin.game-admins.show')
                ->breadcrumb('', 'admin.game-admins.index');
        });

        Route::controller(PlayersController::class)->prefix('players')->group(function () {
            Route::get('/', 'index')->name('admin.players.index')->breadcrumb('Players');
            Route::get('/{player}', 'show')
                ->whereNumber('player')
                ->name('admin.players.show')
                ->breadcrumb('', 'admin.players.index');
            Route::get('/{ckey}', 'showByCkey')
                ->whereAlphaNumeric('ckey')
                ->name('admin.player.show-by-ckey');
        });

        Route::controller(BansController::class)->prefix('bans')->group(function () {
            Route::get('/', 'index')->name('admin.bans.index')->breadcrumb('Bans');
            Route::get('/removed', 'indexRemoved')->name('admin.bans.index-removed')->breadcrumb('Bans');
            Route::get('/details', 'getDetails')->name('admin.bans.get-details');
            Route::post('/details/{ban}', 'storeDetail')->whereNumber('ban')->name('admin.bans.store-detail');
            Route::delete('/details/{banDetail}', 'destroyDetail')->whereNumber('banDetail')->name('admin.bans.destroy-detail');
            Route::get('/create', 'create')->name('admin.bans.create')->breadcrumb('', 'admin.bans.index');
            Route::get('/{ban}', 'show')->whereNumber('ban')->name('admin.bans.show')->breadcrumb('', 'admin.bans.index');
            Route::post('/', 'store')->name('admin.bans.store');
            Route::get('/edit/{ban}', 'edit')->whereNumber('ban')->name('admin.bans.edit')->breadcrumb('', 'admin.bans.index');
            Route::put('/{ban}', 'update')->whereNumber('ban')->name('admin.bans.update');
            Route::delete('/{ban}', 'destroy')->whereNumber('ban')->name('admin.bans.delete');
            Route::delete('/', 'destroyMulti')->name('admin.bans.delete-multi');
        });

        Route::controller(PlayerNotesController::class)->prefix('notes')->group(function () {
            Route::get('/', 'index')->name('admin.notes.index')->breadcrumb('Notes');
            Route::get('/create', 'create')->name('admin.notes.create')->breadcrumb('', 'admin.notes.index');
            Route::get('/{note}', 'show')->whereNumber('note')->name('admin.notes.show')->breadcrumb('', 'admin.notes.index');
            Route::post('/', 'store')->name('admin.notes.store');
            Route::get('/edit/{note}', 'edit')->whereNumber('note')->name('admin.notes.edit')->breadcrumb('', 'admin.notes.index');
            Route::put('/{note}', 'update')->whereNumber('note')->name('admin.notes.update');
            Route::delete('/{note}', 'destroy')->whereNumber('note')->name('admin.notes.delete');
            Route::delete('/', 'destroyMulti')->name('admin.notes.delete-multi');
        });

        Route::controller(MapsController::class)->prefix('maps')->group(function () {
            Route::get('/', 'index')->name('admin.maps.index')->breadcrumb('Maps');
            Route::get('/upload', 'showUpload')->name('admin.maps.upload')->breadcrumb('', 'admin.maps.index');
            Route::post('/upload', 'upload')->name('admin.maps.upload-update');
            Route::post('/upload-file', 'uploadFile')->name('admin.maps.upload-file');
            Route::get('/create', 'create')->name('admin.maps.create')->breadcrumb('', 'admin.maps.index');
            Route::post('/', 'store')->name('admin.maps.store');
            Route::get('/edit/{map}', 'edit')->whereNumber('map')->name('admin.maps.edit')->breadcrumb('', 'admin.maps.index');
            Route::put('/{map}', 'update')->whereNumber('map')->name('admin.maps.update');
            Route::delete('/{map}', 'destroy')->whereNumber('map')->name('admin.maps.delete');
        });

        Route::controller(EventsController::class)->prefix('events')->group(function () {
            Route::get('/', 'index')->name('admin.events.index')->breadcrumb('Events');
        });

        Route::controller(LogsController::class)->prefix('logs')->group(function () {
            Route::get('/', 'index')->name('admin.logs.index')->breadcrumb('Logs');
            Route::get('/{gameRound}', 'show')
                ->whereNumber('gameRound')
                ->name('admin.logs.show')
                ->breadcrumb('', 'admin.logs.index');
        });
    });
});
