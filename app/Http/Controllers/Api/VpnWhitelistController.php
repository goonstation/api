<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\VpnWhitelistResource;
use App\Models\GameAdmin;
use App\Models\VpnWhitelist;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class VpnWhitelistController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<VpnWhitelistResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return VpnWhitelistResource::collection(
            $this->indexQuery(VpnWhitelist::with(['gameAdmin:id,ckey,name']))
        );
    }

    /**
     * Add
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'game_admin_ckey' => 'exists:game_admins,ckey',
            'ckey' => 'required',
        ]);

        $gameAdmin = null;
        if (isset($data['game_admin_ckey'])) {
            $gameAdmin = GameAdmin::where('ckey', $data['game_admin_ckey'])->first();
        }

        $entry = VpnWhitelist::firstOrCreate(
            ['ckey' => $data['ckey']],
            [
                'game_admin_id' => $gameAdmin ? $gameAdmin->id : null,
            ]
        );

        return new VpnWhitelistResource($entry);
    }

    /**
     * Delete
     */
    public function destroy(VpnWhitelist $vpnWhitelist)
    {
        $vpnWhitelist->delete();

        return ['message' => 'VPN check whitelist entry removed'];
    }
}
