<?php

return [
    'include_frontend' => env('INCLUDE_FRONTEND', false),

    'ci_url' => env('GOONHUB_CI_URL', null),
    'ci_api_key' => env('GOONHUB_CI_API_KEY', null),

    'game_bridge_url' => env('GAME_BRIDGE_URL', null),
    'game_bridge_user' => env('GAME_BRIDGE_USER', null),
    'game_bridge_pass' => env('GAME_BRIDGE_PASS', null),

    'ipquality_pass' => env('IPQUALITY_PASS', null),

    'byond_user' => env('BYOND_USER', null),
    'byond_pass' => env('BYOND_PASS', null),

    'maxmind_license_key' => env('MAXMIND_LICENSE_KEY', null),

    'discord_bot_url' => env('DISCORD_BOT_URL', null),
    'discord_bot_key' => env('DISCORD_BOT_KEY', null),
];
