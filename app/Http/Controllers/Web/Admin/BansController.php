<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Api\BansController as ApiBansController;
use App\Http\Controllers\Controller;
use App\Http\Requests\BanRequest;
use App\Models\Ban;
use App\Models\BanDetail;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BansController extends Controller
{
    use IndexableQuery;

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
        app(ApiBansController::class)->store($request);

        return to_route('admin.bans.index');
    }
}
