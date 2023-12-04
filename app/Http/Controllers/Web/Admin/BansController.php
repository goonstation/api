<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BanRequest;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Traits\IndexableQuery;
use App\Traits\ManagesBans;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BansController extends Controller
{
    use IndexableQuery, ManagesBans;

    public function index(Request $request)
    {
        $bans = $this->indexQuery(
            Ban::withCount(['details'])
                ->with(['originalBanDetail', 'gameAdmin', 'gameServer'])
                ->where('expires_at', '>', Carbon::now())
                ->orWhere('expires_at', null),
            perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Bans/Index', [
                'bans' => $bans,
            ]);
        } else {
            return $bans;
        }
    }

    public function indexRemoved(Request $request)
    {
        $bans = $this->indexQuery(
            Ban::withTrashed()
                ->withCount(['details'])
                ->with(['originalBanDetail', 'gameAdmin', 'gameServer'])
                ->where('deleted_at', '!=', null)
                ->orWhere('expires_at', '<=', Carbon::now()),
            perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Bans/IndexRemoved', [
                'bans' => $bans,
            ]);
        } else {
            return $bans;
        }
    }

    public function create()
    {
        return Inertia::render('Admin/Bans/Create');
    }

    public function store(BanRequest $request)
    {
        $request->merge([
            'game_admin_ckey' => Auth::user()->gameAdmin->ckey,
        ]);
        $this->addBan($request);

        return to_route('admin.bans.index');
    }

    public function edit(Ban $ban)
    {
        $ban->load('originalBanDetail');

        return Inertia::render('Admin/Bans/Edit', [
            'ban' => $ban,
        ]);
    }

    public function update(BanRequest $request, Ban $ban)
    {
        try {
            $request = $request->merge([
                'game_admin_ckey' => Auth::user()->gameAdmin->ckey,
            ]);
            $this->updateBan($request, $ban);
        } catch (Exception $e) {
            return Redirect::back()->withErrors(['error' => $e->getMessage()]);
        }

        return to_route('admin.bans.index');
    }

    public function show(int $ban)
    {
        $ban = Ban::withTrashed()
            ->with([
                'originalBanDetail',
                'gameAdmin',
                'deletedByGameAdmin',
                'gameServer',
                'details' => function ($q) {
                    $q->where('deleted_at', null)
                        ->orderBy('id', 'desc');
                },
            ])
            ->findOrFail($ban);

        return Inertia::render('Admin/Bans/Show', [
            'ban' => $ban,
        ]);
    }

    public function destroy(Ban $ban)
    {
        $ban->deleted_by = Auth::user()->gameAdmin->id;
        $ban->save();
        $ban->delete();

        return ['message' => 'Ban removed'];
    }

    public function destroyMulti(Request $request)
    {
        $data = $this->validate($request, [
            'ids' => 'required|array',
        ]);

        $bans = Ban::whereIn('id', $data['ids']);
        $bans->update(['deleted_by' => Auth::user()->gameAdmin->id]);
        $bans->delete();

        return ['message' => 'Bans removed'];
    }

    public function getDetails(Request $request)
    {
        return BanDetail::withTrashed()
            ->where('ban_id', $request->input('ban_id'))
            ->orderBy('id', 'desc')
            ->get();
    }

    public function storeDetail(Request $request, Ban $ban)
    {
        $data = $this->validate($request, [
            'ckey' => 'required_without_all:comp_id,ip|nullable',
            'comp_id' => 'required_without_all:ckey,ip|nullable',
            'ip' => 'required_without_all:ckey,comp_id|nullable|ip',
        ]);

        $banDetail = new BanDetail();
        $banDetail->ckey = isset($data['ckey']) ? $data['ckey'] : null;
        $banDetail->comp_id = isset($data['comp_id']) ? $data['comp_id'] : null;
        $banDetail->ip = isset($data['ip']) ? $data['ip'] : null;
        $ban->details()->save($banDetail);

        return ['data' => $banDetail];
    }

    public function destroyDetail(BanDetail $banDetail)
    {
        $banDetail->delete();

        return ['message' => 'Ban detail removed'];
    }
}
