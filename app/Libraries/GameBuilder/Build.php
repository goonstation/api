<?php

namespace App\Libraries\GameBuilder;

use App\Facades\GameBridge;
use App\Libraries\DiscordBot;
use App\Models\GameAdmin;
use App\Models\GameBuild;
use App\Models\GameBuildSecret;
use App\Models\GameBuildSetting;
use App\Models\GameBuildTestMerge;
use App\Models\GameServer;
use Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process as FacadesProcess;
use Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Symfony\Component\Process\Process;
use ZipArchive;

class Build
{
    private GameServer $server;

    private GameAdmin $admin;

    private Repo $repo;

    private GameBuild $model;

    private GameBuildSetting $settings;

    private $mapSwitch = false;

    private $rootDir;

    private $tmpDir;

    private $rootByondDir;

    private $rootRustgDir;

    private $byondDir;

    private $serverDir;

    private $buildDir;

    private $buildCdnDir;

    private $deployDir;

    private $rustgDir;

    private $deployTargetRoot = '/remote-game';

    private $deployTarget;

    private $cdnTargetRoot = '/cdn';

    private $cdnTarget;

    private $cdnNodeVersion = '18.20.5';

    private $testMergeBranch;

    private $testMergeSuccesses = [];

    private $testMergeConflicts = [];

    private $error;

    private $cancelled = false;

    private $procCacheKey;

    private $cancelCacheKey;

    public function __construct(GameServer $server, GameAdmin $admin, bool $mapSwitch = false)
    {
        $this->server = $server;
        $this->admin = $admin;
        $this->settings = GameBuildSetting::with(['map'])
            ->where('server_id', $server->server_id)
            ->first();

        $this->mapSwitch = $mapSwitch && $this->settings->map_id ? true : false;

        $this->rootDir = storage_path('app/game-builder');
        $this->tmpDir = "{$this->rootDir}/tmp";
        $this->rootByondDir = "{$this->rootDir}/byond";
        $this->rootRustgDir = "{$this->rootDir}/rustg";
        $this->serverDir = "{$this->rootDir}/servers/{$server->server_id}";
        $this->buildDir = "{$this->serverDir}/build";
        $this->buildCdnDir = "{$this->serverDir}/buildcdn";
        $this->deployDir = "{$this->serverDir}/deploy";

        $byondVersion = "{$this->settings->byond_major}.{$this->settings->byond_minor}";
        $this->byondDir = "{$this->rootByondDir}/$byondVersion";

        $this->deployTarget = "{$this->deployTargetRoot}/servers/{$server->server_id}";
        $this->cdnTarget = "{$this->cdnTargetRoot}/{$server->server_id}";

        $this->procCacheKey = "GameBuild-{$this->server->server_id}-proc";
        Cache::forget($this->procCacheKey);
        $this->cancelCacheKey = "GameBuild-{$this->server->server_id}-cancel";
        Cache::forget($this->cancelCacheKey);

        $this->repo = new Repo($this, $this->serverDir);

        if (File::missing($this->rootDir)) {
            File::makeDirectory($this->rootDir);
        }

        $this->log("Created build object for {$server->server_id}");
        if ($this->mapSwitch) {
            $this->log("Map switch build detected: {$this->settings->map->name}");
        }
    }

    public function log($msg)
    {
        echo '['.date('Y-m-d H:i:s').'] '.$msg.PHP_EOL;
    }

    public function runProcess(Process $process)
    {
        $process->start();
        Cache::set($this->procCacheKey, $process->getPid());
        $process->wait();
        Cache::forget($this->procCacheKey);
    }

    public function checkCancelled()
    {
        if (Cache::has($this->cancelCacheKey)) {
            Cache::forget($this->cancelCacheKey);
            throw new CancelledException;
        }
    }

    public static function cancel(string $serverId, int $adminId)
    {
        Cache::set("GameBuild-$serverId-cancel", $adminId);
        $pid = Cache::get("GameBuild-$serverId-proc");
        if ($pid) {
            FacadesProcess::run("kill -9 $pid");
        }
    }

    private function createModel()
    {
        $this->checkCancelled();
        $this->log('Creating model');
        $gameBuild = new GameBuild;
        $gameBuild->server_id = $this->server->server_id;
        $gameBuild->started_by = $this->admin->id;
        $gameBuild->branch = $this->settings->branch;
        $gameBuild->map_switch = $this->mapSwitch;
        $gameBuild->map_id = $this->settings->map_id;
        $gameBuild->save();

        $this->model = $gameBuild;
    }

    private function mergeTestMerges()
    {
        $this->checkCancelled();
        $testMerges = GameBuildTestMerge::where('server_id', $this->server->server_id)
            ->get();

        if ($testMerges->isEmpty()) {
            $this->log('[Test Merges] None found');

            return;
        }

        // Create and checkout testmerge branch if test merges found
        $this->testMergeBranch = 'testmerge-'.time();
        $this->repo->createAndCheckoutBranch($this->testMergeBranch);

        foreach ($testMerges as $testMerge) {
            if ($this->mergeTestMerge($testMerge)) {
                $this->testMergeSuccesses[] = $testMerge->pr_id;
            }
        }
    }

    private function mergeTestMerge(GameBuildTestMerge $testMerge)
    {
        $this->checkCancelled();
        $this->log("[Test Merges] Merging PR #{$testMerge->pr_id}");
        $prBranch = "pr-{$testMerge->pr_id}";

        // This fetches the PR and makes a new branch at the latest HEAD
        $this->repo->fetchPr($testMerge->pr_id);
        $this->repo->checkout($prBranch);

        if ($testMerge->commit) {
            // Move the new PR branch to a specific commit if specified
            $this->repo->resetBranchToCommit($testMerge->commit);
        } else {
            // If no commit was specified, save whatever the latest HEAD commit is
            // This is so we don't always update to the latest on future merges, which introduces security risks
            $latestCommitHash = $this->repo->getHash();
            $testMerge->commit = $latestCommitHash;
            $testMerge->save();
        }

        // Move back to our primary test merge branch
        $this->repo->checkout($this->testMergeBranch);

        // Attempt to merge PR in, with handling to skip it if there are conflicts
        try {
            $this->repo->merge($prBranch);
        } catch (ProcessFailedException) {
            $this->log('[Test Merges] Failed to merge due to conflicts');
            $this->testMergeConflicts[] = [
                'prId' => $testMerge->pr_id,
                'files' => $this->repo->getConflictedFiles(),
            ];
            $this->repo->abortMerge();

            return false;
        }

        $this->repo->commit("Testmerge $prBranch");

        return true;
    }

    private function generateBuildDefines()
    {
        $this->checkCancelled();
        $this->log('Generating build defines');
        $localHash = $this->repo->getHash();
        $localAuthor = $this->repo->getAuthor($localHash);
        $originHash = $this->repo->getHash($this->settings->branch);
        $originAuthor = $this->repo->getAuthor($originHash);
        $now = now();

        $defines = [
            '#define LIVE_SERVER',
            "var/global/vcs_revision = \"$localHash\"",
            "var/global/vcs_author = \"$localAuthor\"",
            "#define VCS_REVISION \"$localHash\"",
            "#define VCS_AUTHOR \"$localAuthor\"",
            "#define ORIGIN_REVISION \"$originHash\"",
            "#define ORIGIN_AUTHOR \"$originAuthor\"",
            "#define BUILD_TIME_TIMEZONE_ALPHA \"{$now->getTimezone()->getName()}\"",
            "#define BUILD_TIME_TIMEZONE_OFFSET {$now->getOffset()}",
            "#define BUILD_TIME_FULL \"{$now->toDateTimeString()}\"",
            "#define BUILD_TIME_YEAR {$now->year}",
            "#define BUILD_TIME_MONTH {$now->month}",
            "#define BUILD_TIME_DAY {$now->day}",
            "#define BUILD_TIME_HOUR {$now->hour}",
            "#define BUILD_TIME_MINUTE {$now->minute}",
            "#define BUILD_TIME_SECOND {$now->second}",
            "#define BUILD_TIME_UNIX {$now->unix()}",
            "#define PRELOAD_RSC_URL \"https://cdn-{$this->server->server_id}.goonhub.com/rsc.zip\"",
        ];

        if ($this->settings->map_id) {
            $defines[] = "#define MAP_OVERRIDE_{$this->settings->map_id}";
        }
        if ($this->settings->rp_mode) {
            $defines[] = '#define RP_MODE';
        }
        if (! empty($this->testMergeSuccesses)) {
            $mergedPrIds = implode(',', $this->testMergeSuccesses);
            $defines[] = "#define TESTMERGE_PRS list($mergedPrIds)";

            foreach ($this->testMergeSuccesses as $prId) {
                $defines[] = "#define TESTMERGE_$prId";
            }
        }

        return implode("\n", $defines);
    }

    private function generateSecrets()
    {
        $this->checkCancelled();
        $secrets = GameBuildSecret::all()->map(function ($secret) {
            $key = Str::upper($secret->key);

            return "$key {$secret->value}";
        })->toArray();

        $secrets[] = 'GOONHUB_URL '.config('app.url');
        $secrets[] = 'GOONHUB_API_ENDPOINT '.config('app.api_url');
        $secrets[] = 'GOONHUB_EVENTS_PASSWORD '.config('database.redis.events.password');

        return implode("\n", $secrets);
    }

    private function prepareBuildDir()
    {
        $this->checkCancelled();
        $this->log('Preparing build directory');
        // Ensure build dir exists and is empty
        if (File::exists($this->buildDir)) {
            File::deleteDirectory($this->buildDir, preserve: true);
        } else {
            File::makeDirectory($this->buildDir, recursive: true);
        }

        // Copy repo dir contents to build, without git history
        $process = Process::fromShellCommandline("rsync -a --exclude=.git {$this->repo->repoDir}/ {$this->buildDir}");
        $this->runProcess($process);

        // Defines needed to compile the game correctly
        File::put("{$this->buildDir}/_std/__build.dm", $this->generateBuildDefines());

        // Merge various secret things into the main repo
        File::copyDirectory("{$this->buildDir}/+secret/config", "{$this->buildDir}/config");

        // Add secret tokens to config
        File::append("{$this->buildDir}/config/config.txt", $this->generateSecrets());
    }

    private function updateByond()
    {
        $this->checkCancelled();
        $version = "{$this->settings->byond_major}.{$this->settings->byond_minor}";

        if (File::exists($this->byondDir)) {
            // Byond version already downloaded
            $this->log("[Byond] Already downloaded $version");

            return;
        }

        $this->log('[Byond] Updating');

        if (! File::exists($this->rootByondDir)) {
            File::makeDirectory($this->rootByondDir);
        }

        $workDir = $this->tmpDir.'/byond-'.time();
        File::makeDirectory($workDir, recursive: true);

        Http::sink("$workDir/byond.zip")
            ->get("https://www.byond.com/download/build/{$this->settings->byond_major}/{$version}_byond_linux.zip");

        $zip = new ZipArchive;
        $zip->open("$workDir/byond.zip");
        $zip->extractTo($workDir);
        $zip->close();

        File::moveDirectory("$workDir/byond", $this->byondDir);
        shell_exec("chmod -R 770 {$this->byondDir}");
        File::deleteDirectory($workDir);

        $this->log("[Byond] Downloaded $version");
    }

    private function compile()
    {
        $this->checkCancelled();
        $this->log('[Compile] Compiling');

        // Debug: cause a compile error
        // File::append("{$this->buildDir}/code/world/world.dm", "\nthisProcDoesNotExist()");

        $process = new Process(
            ["{$this->byondDir}/bin/DreamMaker", 'goonstation.dme'],
            cwd: $this->buildDir,
            env: [
                'PATH' => "{$this->byondDir}/bin:".getenv('PATH'),
                'LD_LIBRARY_PATH' => "{$this->byondDir}/bin",
            ],
            timeout: 300 // 5 minutes
        );
        $this->runProcess($process);

        $this->log('[Compile] Success');
    }

    private function prepareDeployDir()
    {
        $this->checkCancelled();
        $this->log('Preparing deploy directory');
        // Ensure deploy dir exists and is "empty"
        if (File::exists($this->deployDir)) {
            File::deleteDirectory($this->deployDir, preserve: true);
        } else {
            File::makeDirectory($this->deployDir, recursive: true);
        }

        File::makeDirectory("{$this->deployDir}/+secret", recursive: true);
    }

    private function prepareCompiledAssetsForDeploy()
    {
        $this->checkCancelled();
        $this->log('Preparing compiled assets for deployment');
        $files = ['goonstation.dmb', 'goonstation.rsc', 'buildByond.conf'];
        $dirs = ['assets', 'config', 'strings', 'sound', 'tools'];
        $secretDirs = ['assets', 'strings'];

        foreach ($files as $file) {
            File::move("{$this->buildDir}/$file", "{$this->deployDir}/$file");
        }
        foreach ($dirs as $dir) {
            File::moveDirectory("{$this->buildDir}/$dir", "{$this->deployDir}/$dir");
        }
        foreach ($secretDirs as $dir) {
            File::moveDirectory("{$this->buildDir}/+secret/$dir", "{$this->deployDir}/+secret/$dir");
        }

        // Dump test merge PR details to json files that the game can later read for whatever
        foreach ($this->testMergeSuccesses as $prId) {
            File::makeDirectory("{$this->deployDir}/testmerges");
            Http::sink("{$this->deployDir}/testmerges/$prId.json")
                ->get("https://api.github.com/repos/goonstation/goonstation/pulls/$prId");
        }

        $zip = new ZipArchive;
        $zip->open("{$this->deployDir}/rsc.zip", ZipArchive::CREATE);
        $zip->addFile("{$this->deployDir}/goonstation.rsc", 'goonstation.rsc');
        $zip->close();
    }

    private function buildRustg()
    {
        $this->checkCancelled();
        $this->rustgDir = "{$this->rootRustgDir}/{$this->settings->rustg_version}";

        if (File::exists($this->rustgDir)) {
            // Rust-g version already built
            $this->log("[Rust-G] Already built {$this->settings->rustg_version}");

            return;
        }

        $this->log('[Rust-G] Updating');

        if (! File::exists($this->rootRustgDir)) {
            File::makeDirectory($this->rootRustgDir);
        }

        $workDir = $this->tmpDir.'/rustg-'.time();
        File::makeDirectory($workDir, recursive: true);

        $process = new Process([
            'git', 'clone', '--depth', 1, '--branch', $this->settings->rustg_version,
            'https://github.com/goonstation/rust-g', $workDir,
        ]);
        $this->runProcess($process);

        $cargoBin = getenv('HOME').'/.cargo/bin';
        $process = new Process(
            [
                "$cargoBin/cargo", 'build', '--release', '--target', 'i686-unknown-linux-gnu', '--features', 'all',
            ],
            cwd: $workDir,
            env: [
                'RUSTFLAGS' => '-C target-cpu=native',
                'PKG_CONFIG_ALLOW_CROSS' => 1,
            ],
            timeout: 300
        );
        $this->runProcess($process);

        File::makeDirectory($this->rustgDir);
        File::copy("$workDir/target/i686-unknown-linux-gnu/release/librust_g.so", "{$this->rustgDir}/librust_g.so");
        shell_exec("chmod -R 770 {$this->rustgDir}");
        File::deleteDirectory($workDir);

        $this->log("[Rust-G] Updated to {$this->settings->rustg_version}");
    }

    private function buildCdn()
    {
        $this->checkCancelled();
        if (File::exists($this->buildCdnDir)) {
            $process = new Process([
                'diff', '-qr',
                '-x', 'node_modules',
                '-x', 'build',
                '-x', 'revision',
                '-x', 'package-lock.json',
                'browserassets',
                $this->buildCdnDir,
            ], $this->buildDir);
            $process->run();

            if (! $process->getOutput()) {
                // CDN files haven't changed, avoid rebuilding
                $this->log('[CDN] No changes');

                return;
            }
        } else {
            File::makeDirectory($this->buildCdnDir);
        }

        $this->log('[CDN] Building');

        $nvm = getenv('NVM_DIR');
        $nvmUse = fn ($cmd) => sprintf("bash -c '. %s/nvm.sh ; %s ;'", $nvm, $cmd);

        // Install target Node version if missing
        if (File::missing("$nvm/versions/node/v{$this->cdnNodeVersion}")) {
            $this->log('[CDN] Missing target Node version, installing');
            $process = Process::fromShellCommandline($nvmUse("nvm install {$this->cdnNodeVersion}"));
            $this->runProcess($process);
        }

        // Clean out old CDN build dir, but keep node modules so we don't have to waste time reinstalling them all
        File::moveDirectory("{$this->buildCdnDir}/node_modules", "{$this->serverDir}/node_modules");
        File::deleteDirectory($this->buildCdnDir, preserve: true);
        File::moveDirectory("{$this->serverDir}/node_modules", "{$this->buildCdnDir}/node_modules");

        $process = Process::fromShellCommandline("mv {$this->buildDir}/browserassets/* {$this->buildCdnDir}");
        $this->runProcess($process);

        File::put("{$this->buildCdnDir}/revision", $this->repo->getHash());

        $process = Process::fromShellCommandline(
            $nvmUse("nvm exec {$this->cdnNodeVersion} npm install --no-progress"),
            $this->buildCdnDir, timeout: 300
        );
        $this->runProcess($process);
        $process = Process::fromShellCommandline(
            $nvmUse("nvm exec {$this->cdnNodeVersion} npm run build -- --servertype {$this->server->server_id}"),
            $this->buildCdnDir, timeout: 300
        );
        $this->runProcess($process);

        File::moveDirectory("{$this->buildCdnDir}/build", "{$this->deployDir}/cdn");
        $this->log('[CDN] Built');
    }

    private function deploy()
    {
        $this->checkCancelled();
        $this->log('Deploying');

        // Byond
        $process = Process::fromShellCommandline("rsync -ar --ignore-existing {$this->rootByondDir}/* {$this->deployTargetRoot}/byond/");
        $this->runProcess($process);

        // Rust-G
        $process = Process::fromShellCommandline("rsync -ar --ignore-existing {$this->rootRustgDir}/* {$this->deployTargetRoot}/rust-g/");
        $this->runProcess($process);

        // CDN
        if (! File::exists($this->cdnTarget)) {
            File::makeDirectory($this->cdnTarget);
        }
        $process = Process::fromShellCommandline("mv {$this->deployDir}/rsc.zip {$this->cdnTarget}/");
        $this->runProcess($process);
        if (File::exists("{$this->deployDir}/cdn")) {
            $process = Process::fromShellCommandline("rsync -rl {$this->deployDir}/cdn/* {$this->cdnTarget}/ && rm -r {$this->deployDir}/cdn");
            $this->runProcess($process);
        }

        // Stamp runtime tool versions so the game startup script can pick the right stuff
        $buildEnv = [
            "BYOND_DIR={$this->settings->byond_major}.{$this->settings->byond_minor}",
            "RUSTG_DIR={$this->settings->rustg_version}",
        ];
        File::put("{$this->deployDir}/.env.build", implode("\n", $buildEnv));

        // Game
        $gameUpdateDir = "{$this->deployTarget}/game/update";
        if (! File::exists($gameUpdateDir)) {
            File::makeDirectory($gameUpdateDir, recursive: true);
        }
        $process = Process::fromShellCommandline("rm -r $gameUpdateDir/* >/dev/null 2>&1");
        $process->run();
        $process = Process::fromShellCommandline("mv {$this->deployDir}/* $gameUpdateDir/");
        $this->runProcess($process);
        $process = Process::fromShellCommandline("mv {$this->deployDir}/.* $gameUpdateDir/");
        $this->runProcess($process);
    }

    private function buildFull()
    {
        $this->checkCancelled();
        $this->repo->fetch();
        $this->repo->reset();

        $this->model->commit = $this->repo->getHash();
        $this->model->save();

        $this->mergeTestMerges();
        $this->updateByond();
        $this->prepareBuildDir();
        $this->compile();

        $this->prepareDeployDir();
        $this->prepareCompiledAssetsForDeploy();

        $this->buildRustg();
        $this->buildCdn();
    }

    private function buildMapSwitch()
    {
        $this->checkCancelled();
        $this->model->commit = $this->repo->getHash();
        $this->model->save();

        $this->mergeTestMerges();
        $this->prepareBuildDir();
        $this->compile();

        $this->prepareDeployDir();
        $this->prepareCompiledAssetsForDeploy();
    }

    public function start()
    {
        $this->log('Starting build');

        try {
            $this->createModel();

            $this->log('Resetting repo');
            $this->repo->init();
            $this->repo->checkoutRemote($this->settings->branch);

            if ($this->mapSwitch) {
                $this->buildMapSwitch();
            } else {
                $this->buildFull();
            }

            $this->deploy();

            if ($this->mapSwitch) {
                $this->notifyGameOfMapSwitch();
            }

        } catch (ProcessFailedException $e) {
            $process = $e->getProcess();
            $cmd = $process->getCommandLine();
            $message = $process->getErrorOutput();
            if (! $message) {
                $message = $process->getOutput();
            }
            $this->log("[PROCESS EXCEPTION] $cmd\n $message");
            $this->error = $message ? $message : true;

        } catch (ProcessSignaledException|CancelledException) {
            $cancelledBy = Cache::get($this->cancelCacheKey);
            Cache::forget($this->cancelCacheKey);

            $this->cancelled = true;
            $this->model->cancelled = true;
            $this->model->cancelled_by = $cancelledBy;
            $this->log('[CANCELLED]');

        } catch (\Throwable $e) {
            $test = $e::class;
            $message = $e->getMessage();
            $this->log("[EXCEPTION] $test $message");
            $this->error = $message ? $message : true;
        }

        $this->model->ended_at = now();
        $this->model->failed = (bool) $this->error;
        $this->model->save();

        $this->notifyDiscordBot();

        $this->log('Finished build');
    }

    private function notifyDiscordBot()
    {
        if (! App::isProduction()) {
            return;
        }
        $this->log('Notifying Discord bot');

        $commit = $this->repo->getHash($this->settings->branch);
        $data = [
            'server' => $this->server->server_id,
            'server_name' => $this->server->name,
            'last_compile' => $this->error,
            'branch' => $this->settings->branch,
            'author' => $this->repo->getAuthor($commit),
            'message' => $this->repo->getMessage($commit),
            'mapSwitch' => $this->mapSwitch,
            'commit' => $commit,
            'error' => (bool) $this->error,
            'cancelled' => $this->cancelled,
            'mergeConflicts' => $this->testMergeConflicts,
        ];

        try {
            DiscordBot::export('wireci/build_finished', 'POST', $data);
        } catch (\Throwable) {
            // ignore
        }
    }

    private function notifyGameOfMapSwitch()
    {
        $this->checkCancelled();
        if (! App::isProduction()) {
            return;
        }
        $this->log('Notifying game of map switch');

        // TODO: some way to cancel bridge comm
        GameBridge::create()
            ->target($this->server->server_id)
            ->message([
                'type' => 'mapSwitchDone',
                'map' => $this->error ? 'FAILED' : $this->settings->map_id,
            ])
            ->sendAndForget();
    }
}
