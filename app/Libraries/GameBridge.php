<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class GameBridge
{
    public static function getConfig()
    {
        return [
            'url' => config('goonhub.game_bridge_url'),
            'user' => config('goonhub.game_bridge_user'),
            'pass' => config('goonhub.game_bridge_pass'),
        ];
    }

    /**
     * Get status of a game server
     *
     * @param  mixed  $data
     * @return string|void
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public static function status(string $server = 'dev')
    {
        $config = GameBridge::getConfig();
        if (! $config['url']) {
            return;
        }
        $url = $config['url'];
        $response = Http::get("$url/status", ['server' => $server]);

        return $response->throw()->json()['response'];
    }

    /**
     * Query a game server
     *
     * @param  mixed  $data
     * @return string|void
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public static function relay(string $server = 'dev', $data = '')
    {
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        $data = urlencode($data);

        $config = GameBridge::getConfig();
        if (! $config['url']) {
            return;
        }
        $url = $config['url'];
        $response = Http::withBasicAuth($config['user'], $config['pass'])
            ->get("$url/wiz/relay/?server=$server&data=$data");

        return $response->throw()->json()['response'];
    }
}
