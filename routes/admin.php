<?php

use App\Http\Controllers\Web\Admin\BansController;
use App\Http\Controllers\Web\Admin\GameAdminRanksController;
use App\Http\Controllers\Web\Admin\GameAdminsController;
use App\Http\Controllers\Web\Admin\MapsController;
use App\Http\Controllers\Web\Admin\PlayersController;
use App\Http\Controllers\Web\Admin\UsersController;
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
        Route::controller(UsersController::class)->prefix('users')->group(function () {
            Route::get('/', 'index')->name('admin.users.index')->breadcrumb('Users');
            Route::get('/edit/{user}', 'edit')->name('admin.users.edit')->breadcrumb('', 'admin.users.index');
            Route::put('/{user}', 'update')->name('admin.users.update');
        });

        Route::controller(GameAdminRanksController::class)->prefix('game-admin-ranks')->group(function () {
            Route::get('/', 'index')->name('admin.game-admin-ranks.index')->breadcrumb('Admin Ranks');
            Route::get('/create', 'create')->name('admin.game-admin-ranks.create')->breadcrumb('', 'admin.game-admin-ranks.index');
            Route::post('/', 'store')->name('admin.game-admin-ranks.store');
        });

        Route::controller(GameAdminsController::class)->prefix('game-admins')->group(function () {
            Route::get('/', 'index')->name('admin.game-admins.index')->breadcrumb('Admins');
            Route::get('/{gameAdmin}', 'show')->name('admin.game-admins.show')->breadcrumb('', 'admin.game-admins.index');
        });

        Route::controller(PlayersController::class)->prefix('players')->group(function () {
            Route::get('/', 'index')->name('admin.players.index')->breadcrumb('Players');
            Route::get('/{player}', 'show')->where('player', '[0-9]+')->name('admin.players.show')->breadcrumb('', 'admin.players.index');
            Route::get('/{ckey}', 'showByCkey')->name('admin.player.show-by-ckey');
        });

        Route::controller(BansController::class)->prefix('bans')->group(function () {
            Route::get('/', 'index')->name('admin.bans.index')->breadcrumb('Bans');
            Route::get('/removed', 'indexRemoved')->name('admin.bans.index-removed')->breadcrumb('Bans');
            Route::get('/details', 'getDetails');
            Route::get('/{ban}', 'show')->name('admin.bans.show')->breadcrumb('', 'admin.bans.index');
            Route::get('/create', 'create')->name('admin.bans.create')->breadcrumb('', 'admin.bans.index');
            Route::post('/', 'store')->name('admin.bans.store');
            Route::get('/edit/{ban}', 'edit')->name('admin.bans.edit')->breadcrumb('', 'admin.bans.index');
            Route::put('/{ban}', 'update')->name('admin.bans.update');
        });

        Route::controller(MapsController::class)->prefix('maps')->group(function () {
            Route::get('/', 'index')->name('admin.maps.index')->breadcrumb('Maps');
            Route::get('/upload', 'showUpload')->name('admin.maps.upload')->breadcrumb('', 'admin.maps.index');
            Route::post('/upload', 'upload')->name('admin.maps.upload-update');
            Route::post('/upload-file', 'uploadFile')->name('admin.maps.upload-file');
            Route::get('/create', 'create')->name('admin.maps.create')->breadcrumb('', 'admin.maps.index');
            Route::post('/', 'store')->name('admin.maps.store');
            Route::get('/edit/{map}', 'edit')->name('admin.maps.edit')->breadcrumb('', 'admin.maps.index');
            Route::put('/{map}', 'update')->name('admin.maps.update');
            Route::delete('/{map}', 'destroy')->name('admin.maps.delete');
        });
    });
});
