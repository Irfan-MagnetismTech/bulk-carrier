<?php

namespace App\Traits;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

trait GlobalSearchTrait
{
    /**
     * Scope a query for a global search.
     *
     * @param mixed $query
     * @param mixed $request
     */
    public function scopeGlobalSearch($query, $request)
    {
        $request = json_decode($request['data']);

        $baseTableQueries       = collect($request->filter_options)->filter(fn ($q) => $q->relation_name === null)->map(function ($item) {
            $item->search_param = trim($item->search_param);
            return $item;
        });
        $relationalTableQueries = collect($request->filter_options)->filter(fn ($q) => $q->relation_name != null)->map(function ($item) {
            $item->search_param = trim($item->search_param);
            return $item;
        });

        $query->when(isset($request->business_unit) && $request->business_unit != "ALL", function ($q) use ($request) {
            $q->where('business_unit', $request->business_unit);
        });

        // Search in base table
        foreach ($baseTableQueries as $key => $baseTableQuery)
        {
            $query->when($baseTableQuery->search_param, fn($q) => $q->where($baseTableQuery->field_name, 'LIKE', '%' . $baseTableQuery->search_param . '%'));

            // $query->when($baseTableQuery->order_by, fn($query) => $query->orderBy($baseTableQuery->field_name, $baseTableQuery->order_by));
        }

        // Search in relationship tables
        foreach ($relationalTableQueries as $key => $relationalTableQuery)
        {
            $query->when($relationalTableQuery->search_param, function ($q) use ($relationalTableQuery)
            {
                $q->whereHas($relationalTableQuery->relation_name, fn($q) => $q->where($relationalTableQuery->field_name, 'LIKE', '%' . $relationalTableQuery->search_param . '%'));
            });
        }

        $query_result = $query->latest()->get();

        //Sorting query result
        foreach ($request->filter_options as $key => $filterOption)
        {
            // Sorting items in base table
            if($filterOption->relation_name === null) {
                if ($filterOption->order_by == "asc")
                {
                    $query_result = $query_result->sortBy(function ($q) use ($filterOption){
                        return $q->{$filterOption->field_name};
                    }, SORT_NATURAL|SORT_FLAG_CASE);
                }

                if ($filterOption->order_by == "desc")
                {
                    $query_result = $query_result->sortByDesc(function ($q) use ($filterOption){
                        return $q->{$filterOption->field_name};
                    }, SORT_NATURAL|SORT_FLAG_CASE);
                }
            } else { // Sorting items in relationship 
                if ($filterOption->order_by == "asc")
                {
                    $query_result = $query_result->sortBy(function ($q) use ($filterOption){
                        $relations = explode(".", $filterOption->relation_name);
                        foreach($relations as $relation) {
                            $q = $q->{$relation};
                        }
                        if ($q)
                            return $q->{$filterOption->field_name};
                    }, SORT_NATURAL|SORT_FLAG_CASE);
                }

                if ($filterOption->order_by == "desc")
                {
                    $query_result = $query_result->sortByDesc(function ($q) use ($filterOption){
                        $relations = explode(".", $filterOption->relation_name);
                        foreach($relations as $relation) {
                            $q = $q->{$relation};
                        }
                        if ($q)
                            return $q->{$filterOption->field_name};
                    }, SORT_NATURAL|SORT_FLAG_CASE);
                }
            }
        }

        $items = $query_result->values()->all();
        $total = count($items);
        $perPage = $request->items_per_page;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = collect(array_slice($items, $perPage * ($currentPage - 1), $perPage));

        $items = new LengthAwarePaginator($currentItems, $total, $perPage);
        return $items;
    }
}
