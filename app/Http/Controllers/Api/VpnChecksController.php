<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VpnCheckResource;
use App\Models\VpnCheck;
use App\Models\VpnWhitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VpnChecksController extends Controller
{
    private $checkCacheTime = 30; // days

    /**
     * Check
     *
     * Check if a player is using a VPN
     */
    public function check(Request $request, string $ip)
    {
        $data = $this->validate($request, [
            'round_id' => 'nullable|integer',
            'ckey' => 'nullable|string',
        ]);

        // Skip checks if whitelisted
        if (! empty($data['ckey'])) {
            $whitelisted = VpnWhitelist::where('ckey', $data['ckey'])->first();
            if ($whitelisted) {
                return [
                    'data' => null,
                    'meta' => [
                        'cached' => false,
                        'whitelisted' => true,
                    ],
                ];
            }
        }

        // Return cached response if available, and still valid
        $checked = VpnCheck::where('ip', $ip)->first();
        if ($checked && $checked->updated_at->diffInDays() <= $this->checkCacheTime) {
            return (new VpnCheckResource($checked))->additional(['meta' => [
                'cached' => true,
                'whitelisted' => false,
            ]]);
        }

        $res = Http::get(
            'https://ipqualityscore.com/api/json/ip/'
                .config('vpn-checks.ipquality_pass')
                .'/'.$ip,
            [
                'allow_public_access_points' => 'true',
                'fast' => 'true',
                'lighter_penalties' => 'true',
                'strictness' => 0,
            ]
        );

        $jsonRes = $res->getBody();
        $res = json_decode($jsonRes);
        $vpnCheck = VpnCheck::updateOrCreate(
            ['ip' => $ip],
            [
                'round_id' => ! empty($data['round_id']) ? $data['round_id'] : null,
                'service' => 'ipqualityscore',
                'error' => ! $res->success ? $res->message : null,
                'response' => $jsonRes,
            ]
        );

        return (new VpnCheckResource($vpnCheck))->additional(['meta' => [
            'cached' => false,
            'whitelisted' => false,
        ]]);
    }
}
