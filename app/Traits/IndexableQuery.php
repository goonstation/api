<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IndexableQuery
{
    /**
     * Paginate/filter/sort a model
     *
     * @param  mixed  $model A model class FQDN, or a query builder for one
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function indexQuery($model, $filter = null, $sortBy = 'id', $desc = 'true', $perPage = 15, $simple = false)
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

        if ($simple) {
            $query = $query->simplePaginateFilter(min((int) $request->input('per_page', $perPage), 100));
        } else {
            $query = $query->paginateFilter(min((int) $request->input('per_page', $perPage), 100));
        }

        return $query;
    }
}
