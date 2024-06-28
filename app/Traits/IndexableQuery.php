<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IndexableQuery
{
    /**
     * Paginate/filter/sort a model
     *
     * @param  mixed  $model  A model class FQDN, or a query builder for one
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function indexQuery($model, $filter = null, $sortBy = 'id', $desc = 'true', $perPage = 15, $paginate = true)
    {
        $request = request();
        if (! is_a($model, Builder::class)) {
            $model = $model::query();
        }

        $filtering = null;
        if ($filter) {
            $filtering = $model->filter($request->input('filters', []), $filter);
        } else {
            $filtering = $model->filter($request->input('filters', []));
        }

        $desc = $request->input('descending', $desc);
        $query = $filtering
            ->orderBy(
                $request->input('sort_by', $sortBy),
                $desc === 'true' || $desc === '1' ? 'desc' : 'asc'
            );

        if ($paginate) {
            $maxPerPage = 100;
            $perPage = (int) $request->input('per_page', $perPage);
            if ($perPage > $maxPerPage && ! $request->user()?->isAdmin()) {
                $perPage = $maxPerPage;
            }

            // $query = $query->simplePaginateFilter($perPage);
            $query = $query->paginateFilter($perPage);
        }

        return $query;
    }
}
