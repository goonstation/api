<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BanRequest;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Traits\IndexableQuery;
use App\Traits\ManagesBans;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BansController extends Controller
{
    use IndexableQuery, ManagesBans;

    public function index(Request $request)
    {
        $bans = $this->indexQuery(
            Ban::withTrashed()
                ->withCount(['details'])
                ->with(['originalBanDetail', 'gameAdmin']
                ), perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Bans/Index', [
                'bans' => $bans,
            ]);
        } else {
            return $bans;
        }
    }

    public function getDetails(Request $request)
    {
        return BanDetail::withTrashed()
            ->where('ban_id', $request->input('ban_id'))
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create()
    {
        return Inertia::render('Admin/Bans/Create');
    }

    public function store(BanRequest $request)
    {
        $ban = $this->addBan($request);

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
            $ban = $this->updateBan($request, $ban);
        } catch (Exception $e) {
            return Redirect::back()->withErrors(['error' => $e->getMessage()]);
        }

        return to_route('admin.bans.index');
    }
}
