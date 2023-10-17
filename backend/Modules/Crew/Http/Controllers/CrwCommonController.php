<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Crew\Entities\CrwAgency;
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

    public function getCrewAgencies(Request $request)
    {
        try {
            $business_unit = Auth::user()->business_unit;
            $crwAgencies      = CrwAgency::when($business_unit != "ALL", function ($q) use ($business_unit)
            {
                $q->where('business_unit', $business_unit);
            })->get();

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
