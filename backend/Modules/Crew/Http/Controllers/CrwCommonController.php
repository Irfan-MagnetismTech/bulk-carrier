<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Crew\Entities\CrwAgency;
use Modules\Crew\Entities\CrwAgencyContract;
use Modules\Crew\Entities\CrwCrew;
use Modules\Crew\Entities\CrwCrewAssignment;
use Modules\Crew\Entities\CrwCrewDocument;
use Modules\Crew\Entities\CrwCrewDocumentRenewal;
use Modules\Crew\Entities\CrwCrewProfile;
use Modules\Crew\Entities\CrwRank;
use Modules\Crew\Entities\CrwRecruitmentApproval;

class CrwCommonController extends Controller
{

    public function getCrewRanks(Request $request)
    {
        try {

            $crwRanks      = CrwRank::when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })->get();

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

            $crwAgencies      = CrwAgencyContract::
//            when(request()->business_unit != "ALL", function ($q)
//            {
//                $q->where('business_unit', request()->business_unit);
//            })
//            ->
            when(request()->crw_agency_id != null, function ($q) {
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

            $crewProfiles      = CrwCrewProfile::when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })
            ->with('crwCurrentRank')
            ->get();

            return response()->success('Retrieved Successfully', $crewProfiles, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCrewDocuments(Request $request)
    {
        try {
            $crwAgencies      = CrwCrewDocument::with(['crwCrewDocumentRenewals' => function($q){
                $q->orderBy('issue_date', 'DESC');
            }])
            ->where('crw_crew_profile_id', $request->crw_crew_profile_id)
            ->when(request()->business_unit != "ALL", function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })
            ->orderBy('id', 'DESC')
            ->get();

            return response()->success('Retrieved Successfully', $crwAgencies, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCrewDocumentRenewals(Request $request){

        try {
            $crwDocumentRenewals = CrwCrewDocumentRenewal::where('crw_crew_document_id',$request->crw_crew_document_id)
                ->orderBy('issue_date','DESC')
                ->get();

            return response()->success('Retrieved Successfully', $crwDocumentRenewals, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVesselAssignedCrews(Request $request){

        try {
            $vesselAssignedCrews = CrwCrewAssignment::with('crwCrew:id,full_name,pre_mobile_no')
            ->where('ops_vessel_id', $request->ops_vessel_id)
            ->where('status', "Onboard")
            ->get();

            return response()->success('Retrieved Successfully', $vesselAssignedCrews, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
