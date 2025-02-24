<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

trait IndexableQuery
{
    /**
     * Paginate/filter/sort a model
     *
     * @param  string|Builder  $model  A model class FQDN, or a query builder for one
     * @return \Illuminate\Pagination\LengthAwarePaginator|Builder
     */
    private function indexQuery($model, $filter = null, $sortBy = 'id', $desc = 'true', $perPage = 15, $paginate = true)
    {
        if (! is_a($model, Builder::class)) {
            $model = $model::query();
        }

        $filtering = null;
        if ($filter) {
            $filtering = $model->filter(Request::input('filters', []), $filter);
        } else {
            $filtering = $model->filter(Request::input('filters', []));
        }

        $desc = Request::input('descending', $desc);
        $query = $filtering
            ->orderBy(
                Request::input('sort_by', $sortBy),
                $desc === 'true' || $desc === '1' ? 'desc' : 'asc'
            );

        if ($paginate) {
            $maxPerPage = 100;
            $perPage = (int) Request::input('per_page', $perPage);
            if ($perPage > $maxPerPage && ! Request::user()?->isAdmin()) {
                $perPage = $maxPerPage;
            }

            // $query = $query->simplePaginateFilter($perPage);
            $query = $query->paginateFilter($perPage);
        }

        return $query;
    }
}
