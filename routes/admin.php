<?php

use App\Http\Controllers\Web\Admin\BansController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\ErrorsController;
use App\Http\Controllers\Web\Admin\EventsController;
use App\Http\Controllers\Web\Admin\GameAdminRanksController;
use App\Http\Controllers\Web\Admin\GameAdminsController;
use App\Http\Controllers\Web\Admin\GameAuthCallbackController;
use App\Http\Controllers\Web\Admin\GameBuildsController;
use App\Http\Controllers\Web\Admin\GameBuildSettingsController;
use App\Http\Controllers\Web\Admin\GameBuildTestMergesController;
use App\Http\Controllers\Web\Admin\JobBansController;
use App\Http\Controllers\Web\Admin\LogsController;
use App\Http\Controllers\Web\Admin\MapsController;
use App\Http\Controllers\Web\Admin\MedalsController;
use App\Http\Controllers\Web\Admin\PlayerNotesController;
use App\Http\Controllers\Web\Admin\PlayersController;
use App\Http\Controllers\Web\Admin\RedirectsController;
use App\Http\Controllers\Web\Admin\RoundsController;
use App\Http\Controllers\Web\Admin\UsersController;
use App\Http\Middleware\CanAccessAdminRoutes;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::prefix('/admin')->middleware([CanAccessAdminRoutes::class])->group(function () {
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

        Route::controller(RoundsController::class)->prefix('rounds')->group(function () {
            Route::get('/', 'index')->name('admin.rounds.index')->breadcrumb('Rounds');
            Route::get('/{round}', 'show')
                ->whereNumber('round')
                ->name('admin.rounds.show')
                ->breadcrumb('', 'admin.rounds.index');
        });

        Route::controller(PlayersController::class)->prefix('players')->group(function () {
            Route::get('/', 'index')->name('admin.players.index')->breadcrumb('Players');
            Route::get('/{player}', 'show')
                ->whereNumber('player')
                ->name('admin.players.show')
                ->breadcrumb('', 'admin.players.index');
            Route::get('/{ckey}', 'showByCkey')
                ->name('admin.player.show-by-ckey');
        });

        Route::controller(BansController::class)->prefix('bans')->group(function () {
            Route::get('/', 'index')->name('admin.bans.index')->breadcrumb('Bans');
            Route::get('/removed', 'indexRemoved')->name('admin.bans.index-removed')->breadcrumb('Bans');
            Route::get('/details', 'getDetails')->name('admin.bans.get-details');
            Route::post('/details/{ban}', 'storeDetail')->whereNumber('ban')->name('admin.bans.store-detail');
            Route::put('/details/{banDetail}', 'updateDetail')->whereNumber('banDetail')->name('admin.bans.update-detail');
            Route::delete('/details/{banDetail}', 'destroyDetail')->whereNumber('banDetail')->name('admin.bans.destroy-detail');
            Route::get('/create', 'create')->name('admin.bans.create')->breadcrumb('', 'admin.bans.index');
            Route::get('/{ban}', 'show')->whereNumber('ban')->name('admin.bans.show')->breadcrumb('', 'admin.bans.index');
            Route::post('/', 'store')->name('admin.bans.store');
            Route::get('/edit/{ban}', 'edit')->whereNumber('ban')->name('admin.bans.edit')->breadcrumb('', 'admin.bans.index');
            Route::put('/{ban}', 'update')->whereNumber('ban')->name('admin.bans.update');
            Route::delete('/{ban}', 'destroy')->whereNumber('ban')->name('admin.bans.delete');
            Route::delete('/', 'destroyMulti')->name('admin.bans.delete-multi');

            Route::get('/remove', 'showRemoveDetails')->name('admin.bans.show-remove-details')->breadcrumb('', 'admin.bans.index');
            Route::post('/remove/lookup', 'lookupDetails')->name('admin.bans.lookup-details');
            Route::post('/remove', 'removeLookupDetails')->name('admin.bans.remove-lookup-details');
        });

        Route::controller(JobBansController::class)->prefix('job-bans')->group(function () {
            Route::get('/', 'index')->name('admin.job-bans.index')->breadcrumb('Job Bans');
            Route::get('/create', 'create')->name('admin.job-bans.create')->breadcrumb('', 'admin.job-bans.index');
            Route::get('/{jobBan}', 'show')->whereNumber('jobBan')->name('admin.job-bans.show')->breadcrumb('', 'admin.job-bans.index');
            Route::post('/', 'store')->name('admin.job-bans.store');
            Route::get('/edit/{jobBan}', 'edit')->whereNumber('jobBan')->name('admin.job-bans.edit')->breadcrumb('', 'admin.job-bans.index');
            Route::put('/{jobBan}', 'update')->whereNumber('jobBan')->name('admin.job-bans.update');
            Route::delete('/{jobBan}', 'destroy')->whereNumber('jobBan')->name('admin.job-bans.delete');
            Route::delete('/', 'destroyMulti')->name('admin.job-bans.delete-multi');
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

        Route::controller(MedalsController::class)->prefix('medals')->group(function () {
            Route::get('/', 'index')->name('admin.medals.index')->breadcrumb('Medals');
            Route::get('/create', 'create')->name('admin.medals.create')->breadcrumb('', 'admin.medals.index');
            Route::post('/', 'store')->name('admin.medals.store');
            Route::get('/edit/{medal}', 'edit')->whereNumber('medal')->name('admin.medals.edit')->breadcrumb('', 'admin.medals.index');
            Route::put('/{medal}', 'update')->whereNumber('medal')->name('admin.medals.update');
            Route::delete('/{medal}', 'destroy')->whereNumber('medal')->name('admin.medals.delete');

            Route::get('/unawarded-to-player/{player}', 'medalsPlayerDoesntHave')->name('admin.medals.unawarded-to-player');
            Route::post('/add-to-player', 'addToPlayer')->name('admin.medals.add-to-player');
            Route::delete('/remove-from-player/{player}/{medal}', 'removeFromPlayer')->whereNumber(['player'])->name('admin.medals.remove-from-player');
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
            Route::get('/get-logs/{gameRound}', 'getLogs')
                ->whereNumber('gameRound')
                ->name('admin.logs.get-logs');
        });

        Route::controller(ErrorsController::class)->prefix('errors')->group(function () {
            Route::get('/', 'index')->name('admin.errors.index')->breadcrumb('Errors');
            Route::get('/summary', 'summary')->name('admin.errors.summary')->breadcrumb('Errors');
            Route::get('/{gameRound}', 'show')
                ->whereNumber('gameRound')
                ->name('admin.errors.show')
                ->breadcrumb('', 'admin.errors.index');
            Route::get('/get-errors/{gameRound}', 'getErrors')
                ->whereNumber('gameRound')
                ->name('admin.errors.get-errors');
        });

        Route::controller(RedirectsController::class)->prefix('redirects')->group(function () {
            Route::get('/', 'index')->name('admin.redirects.index')->breadcrumb('Redirects');
            Route::get('/create', 'create')->name('admin.redirects.create')->breadcrumb('', 'admin.redirects.index');
            Route::post('/', 'store')->name('admin.redirects.store');
            Route::get('/edit/{redirect}', 'edit')->whereNumber('redirect')->name('admin.redirects.edit')->breadcrumb('', 'admin.redirects.index');
            Route::put('/{redirect}', 'update')->whereNumber('redirect')->name('admin.redirects.update');
            Route::delete('/{redirect}', 'destroy')->whereNumber('redirect')->name('admin.redirects.delete');
        });

        Route::controller(GameAuthCallbackController::class)->prefix('game-auth-callback')->group(function () {
            Route::post('/', 'informGame')->name('admin.game-auth-callback');
        });

        Route::controller(GameBuildsController::class)->prefix('builds')->group(function () {
            Route::get('/', 'index')->name('admin.builds.index')->breadcrumb('Builds');
            Route::get('/status', 'status')->name('admin.builds.status');
            Route::post('/start', 'store')->name('admin.builds.store');
            Route::get('/{build}', 'show')->whereNumber('build')->name('admin.builds.show')->breadcrumb('', 'admin.builds.index');
            Route::post('/cancel', 'cancel')->name('admin.builds.cancel');
        });

        Route::controller(GameBuildSettingsController::class)->prefix('builds/settings')->group(function () {
            Route::get('/', 'index')->name('admin.builds.settings.index')->breadcrumb('', 'admin.builds.index');
            Route::get('/create', 'create')->name('admin.builds.settings.create')->breadcrumb('', 'admin.builds.settings.index');
            Route::post('/', 'store')->name('admin.builds.settings.store');
            Route::get('/{setting}', 'edit')->whereNumber('setting')->name('admin.builds.settings.edit')->breadcrumb('', 'admin.builds.settings.index');
            Route::put('/{setting}', 'update')->whereNumber('setting')->name('admin.builds.settings.update');
            Route::delete('/{setting}', 'destroy')->whereNumber('setting')->name('admin.builds.settings.delete');
        });

        Route::controller(GameBuildTestMergesController::class)->prefix('builds/test-merges')->group(function () {
            Route::get('/', 'index')->name('admin.builds.test-merges.index')->breadcrumb('', 'admin.builds.index');
            Route::get('/create', 'create')->name('admin.builds.test-merges.create')->breadcrumb('', 'admin.builds.test-merges.index');
            Route::post('/', 'store')->name('admin.builds.test-merges.store');
            Route::get('/{testMerge}', 'edit')->whereNumber('testMerge')->name('admin.builds.test-merges.edit')->breadcrumb('', 'admin.builds.test-merges.index');
            Route::put('/{testMerge}', 'update')->whereNumber('testMerge')->name('admin.builds.test-merges.update');
            Route::delete('/{testMerge}', 'destroy')->whereNumber('testMerge')->name('admin.builds.test-merges.delete');
            Route::delete('/', 'destroyMulti')->name('admin.builds.test-merges.delete-multi');
            Route::get('/pr', 'pullRequests')->name('admin.builds.test-merges.pr');
            Route::get('/pr/{prId}', 'pullRequestDetails')->whereNumber('prId')->name('admin.builds.test-merges.pr-details');
            Route::get('/{testMerge}/commit', 'editCommit')->whereNumber('testMerge')->name('admin.builds.test-merges.edit-commit')->breadcrumb('', 'admin.builds.test-merges.index');
            Route::put('/{testMerge}/commit', 'updateCommit')->whereNumber('testMerge')->name('admin.builds.test-merges.update-commit');
            Route::put('/{prId}/commits', 'updateCommits')->whereNumber('prId')->name('admin.builds.test-merges.update-commits');
        });
    });
});
