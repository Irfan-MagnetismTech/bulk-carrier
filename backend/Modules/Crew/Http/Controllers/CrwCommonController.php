<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Crew\Entities\CrwAgency;
use Modules\Crew\Entities\CrwAgencyContract;
use Modules\Crew\Entities\CrwCrew;
use Modules\Crew\Entities\CrwRank;
use Modules\Crew\Entities\CrwRecruitmentApproval;

class CrwCommonController extends Controller
{

    public function getCrewRanks(Request $request)
    {
        try {

            $crwRanks      = CrwRank::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwRanks, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCrewAgencies()
    {
        try {

            $crwAgencies      = CrwAgency::when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })->get();

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCrewAgencyContracts()
    {
        try {

            $crwAgencies      = CrwAgencyContract::when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })
            ->when(request()->crw_agency_id != null, function ($q) {
                    return $q->where('crw_agency_id',request()->crw_agency_id);
            })->get();

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCrewRecruitmentApprovals()
    {
        try {

            $crwAgencies      = CrwRecruitmentApproval::when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })
            ->get();

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCrews()
    {
        try {

            $crwAgencies      = CrwCrew::when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })->with('crwRank:id,name')->get();

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
