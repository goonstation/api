<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditController extends Controller
{
    use IndexableQuery;

    private function getAudits()
    {
        return $this->indexQuery(
            // @phpstan-ignore larastan.relationExistence
            Audit::with(['user'])
        );
    }

    public function index(Request $request)
    {
        return Inertia::render('Admin/Audit/Index', [
            'audits' => Inertia::lazy(fn () => $this->getAudits()),
        ]);
    }

    public function getTypes()
    {
        $types = Audit::select(['auditable_type'])->distinct()->get()->map(function (Audit $audit) {
            return [
                'type' => $audit->auditable_clean_type,
                'label' => $audit->auditable_label,
            ];
        });

        return $types->paginate(999);
    }

    public function show(Audit $audit)
    {
        $audit->load(['user'])->append(['auditable_label']);

        return Inertia::render('Admin/Audit/Show', [
            'audit' => $audit,
            'modified' => $audit->getModified(),
        ]);
    }
}
