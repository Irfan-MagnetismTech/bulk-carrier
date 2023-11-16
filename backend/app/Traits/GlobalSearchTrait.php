<?php

namespace App\Traits;

use Illuminate\Database\Query\Builder;

trait GlobalSearchTrait
{
    /**
     * @param Builder $query
     * @param $request
     */
    public function scopeGlobalSearch($query, $request)
    {
        $request = json_decode($request['data']);

        // $baseTableQueries       = collect($request->filter_options)->filter(fn($q) => $q->relation_name === null);
        // $relationalTableQueries = collect($request->filter_options)->filter(fn($q) => $q->relation_name != null);

        $baseTableQueries       = collect($request->filter_options)->filter(fn($q) => $q->relation_name === null)->map(function ($item) {
            $item->search_param = trim($item->search_param);
            return $item;
        });
        $relationalTableQueries = collect($request->filter_options)->filter(fn($q) => $q->relation_name != null)->map(function ($item) {
            $item->search_param = trim($item->search_param);
            return $item;
        });

        $query->when(isset($request->business_unit) && $request->business_unit != "ALL", function($q) use ($request){
            $q->where('business_unit', $request->business_unit);
        });

        foreach ($baseTableQueries as $key => $baseTableQuery)
        {
            $query->when($baseTableQuery->search_param, fn($q) => $q->where($baseTableQuery->field_name, 'LIKE', '%' . $baseTableQuery->search_param . '%'));

            $query->when($baseTableQuery->order_by, fn($query) => $query->orderBy($baseTableQuery->field_name, $baseTableQuery->order_by));            
        }

        foreach ($relationalTableQueries as $key => $relationalTableQuery)
        {
            $query->when($relationalTableQuery->search_param, function ($q) use ($relationalTableQuery)
            {
                $q->whereHas($relationalTableQuery->relation_name, fn($q) => $q->where($relationalTableQuery->field_name, 'LIKE', '%' . $relationalTableQuery->search_param . '%'));                
            });
        }

        return $query->paginate($request->items_per_page); 
    }
}
