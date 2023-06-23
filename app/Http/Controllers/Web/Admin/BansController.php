<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\BanDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BansController extends Controller
{
    public function index(Request $request)
    {
        $bans = Ban::withTrashed()
            ->with(['originalBanDetail', 'gameAdmin'])
            ->filter($request->input('filters', []))
            ->orderBy(
                $request->input('sort_by', 'id'),
                $request->input('descending', 'true') === 'true' ? 'desc' : 'asc'
            )
            ->withCount(['details'])
            ->paginateFilter($request->input('per_page', 15));

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
}
