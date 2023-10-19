<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Crew\Entities\CrwRank;

class CrwCommonController extends Controller
{

    /**
     * @param Request $request
     */
    public function getCrewRanks(Request $request)
    {
        try {
            $business_unit = Auth::user()->business_unit;
            $crwRanks      = CrwRank::when($business_unit != "ALL", function ($q) use ($business_unit)
            {
                $q->where('business_unit', $business_unit);
            })->get();

            return response()->success('Retrieved Succesfully', $crwRanks, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @return mixed
     */
    public function dam()
    {
        try {
            // $ranks = CrwRank::query()
            //     ->where(function ($query) use($request) {
            //         $query->where('name', 'like', '%' . $request->name_or_code . '%');
            //         $query->orWhere('code', 'like', '%' . $request->name_or_code . '%');
            //     })
            //     ->when(!empty($request->service), function ($query) use($request) {
            //         $service_ports = $this->servicePorts($request->service)->original['value'];
            //         return $query->whereIn('code', $service_ports);
            //     })
            //     ->limit(10)
            //     ->get();

            $crwCrews = CrwRank::when()->get();

            return response()->success('Retrieved Succesfully', $crwCrews, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
