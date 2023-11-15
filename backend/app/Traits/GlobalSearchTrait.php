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

        $baseTableQueries       = collect($request->filter_options)->filter(fn($q) => $q->relation_name === null);
        $relationalTableQueries = collect($request->filter_options)->filter(fn($q) => $q->relation_name != null);

        $query->when($request->business_unit != "ALL", function($q) use ($request){
            $q->where('business_unit', $request->business_unit);
        });

        foreach ($baseTableQueries as $key => $baseTableQuery)
        {
            $query->when($baseTableQuery->search_param, function ($q) use ($baseTableQuery)
            {
                $q->where($baseTableQuery->field_name, 'LIKE', '%' . $baseTableQuery->search_param . '%')->orderBy($baseTableQuery->field_name, $baseTableQuery->order_by);
            });
        }

        foreach ($relationalTableQueries as $key => $relationalTableQuery)
        {
            $query->when($relationalTableQuery->search_param, function ($q) use ($relationalTableQuery)
            {
                $q->whereHas($relationalTableQuery->relation_name, function ($q) use ($relationalTableQuery)
                {
                    $q->where($relationalTableQuery->field_name, 'LIKE', '%' . $relationalTableQuery->search_param . '%')->orderBy($relationalTableQuery->field_name, $relationalTableQuery->order_by);
                });
            });
        }
    }
}
