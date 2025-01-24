<?php

return [
    'include_frontend' => env('INCLUDE_FRONTEND', false),

    'ci_url' => env('GOONHUB_CI_URL', null),
    'ci_api_key' => env('GOONHUB_CI_API_KEY', null),

    'game_bridge_url' => env('GAME_BRIDGE_URL', null),

    'github_organization' => env('GITHUB_ORGANIZATION', null),
    'github_repo' => env('GITHUB_REPO', null),

    'ipquality_pass' => env('IPQUALITY_PASS', null),

    'byond_user' => env('BYOND_USER', null),
    'byond_pass' => env('BYOND_PASS', null),

    'maxmind_account_id' => env('MAXMIND_ACCOUNT_ID', null),
    'maxmind_license_key' => env('MAXMIND_LICENSE_KEY', null),
    'geoip_update_version' => env('GEOIP_UPDATE_VERSION', '7.1.0'),

    'discord_bot_url' => env('DISCORD_BOT_URL', null),
    'discord_bot_key' => env('DISCORD_BOT_KEY', null),

    'browserless_host' => env('BROWSERLESS_HOST', null),
    'browserless_port' => env('BROWSERLESS_PORT', null),
    'browserless_token' => env('BROWSERLESS_TOKEN', null),

    'server_performance' => [
        'key' => env('SERVER_PERFORMANCE_KEY', null),
        'servers' => [
            'Tartarus' => '51.222.44.203:16420',
            'Asphodel' => '15.235.86.196:16420',
        ],
    ],
];
