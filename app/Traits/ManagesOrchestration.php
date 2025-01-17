<?php

namespace App\Traits;

use App\Models\GameServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait ManagesOrchestration
{
    private function getServerStatus(Request $request)
    {
        $request->validate([
            'server' => 'nullable|string|exists:game_servers,server_id',
        ]);

        $serverId = $request->input('server', null);
        $servers = collect();
        if ($serverId) {
            $servers->add(GameServer::firstWhere('server_id', $serverId));
        } else {
            $servers = GameServer::where('active', true)->orderBy('server_id')->get();
        }

        $cachedData = [];
        foreach ($servers as $server) {
            $cacheKey = "orchestration-status-{$server->server_id}";
            $cacheItem = Cache::get($cacheKey);
            if ($cacheItem) {
                $cachedData[$server->server_id] = $cacheItem;
            }
        }

        if (count($servers) === count($cachedData)) {
            return $cachedData;
        }

        $status = [];
        $orchestratorGroups = $servers->groupBy('orchestrator');
        foreach ($orchestratorGroups as $orchestratorUrl => $group) {
            $params = [];
            if ($serverId) {
                $params['server'] = $serverId;
            }
            $res = Http::get("$orchestratorUrl/status", $params);
            $res->throwUnlessStatus(200);
            $res = $res->json();
            if (is_null($res)) {
                throw new \Exception('Empty response');
            }
            $status = array_merge($status, $res);
        }

        foreach ($status as $resServerId => $singleStatus) {
            $cacheKey = "orchestration-status-$resServerId";
            Cache::set($cacheKey, $singleStatus, 55);
        }

        ksort($status);

        return $status;
    }

    private function restartServer(Request $request)
    {
        $request->validate([
            'server' => 'required|string|exists:game_servers,server_id',
        ]);

        $server = GameServer::firstWhere('server_id', $request['server']);
        $lock = Cache::lock("orchestration-restart-{$server->server_id}", 60);
        if (! $lock->get()) {
            throw new \Exception('Restarted recently');
        }

        $res = Http::get("{$server->orchestrator}/restart", [
            'server' => $server->server_id,
        ]);
        $res->throwUnlessStatus(200);

        Cache::forget("orchestration-status-{$server->server_id}");

        return true;
    }
}
