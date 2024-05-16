<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class DiscordBot
{
    public static function getConfig()
    {
        return [
            'url' => config('goonhub.discord_bot_url'),
            'key' => config('goonhub.discord_bot_key'),
        ];
    }

    /**
     * Export data to a Discord bot
     *
     * @param  string  $route
     * @param  array   $data
     * @return string|void
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public static function export(string $route, array $data)
    {
        $config = DiscordBot::getConfig();
        if (! $config['url']) {
            return;
        }
        $url = $config['url'];

        $data['api_key'] = $config['key'];
        $data['server_name'] = 'Goonhub';
        $data['server'] = 'goonhub';

        $response = Http::get("$url/$route", $data);
        return $response->throw()->json();
    }
}
