<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\AppraisalForm;
use Modules\Crew\Entities\CrwAgency;
use Modules\Crew\Entities\CrwAgencyContract;
use Modules\Crew\Entities\CrwAttendance;
use Modules\Crew\Entities\CrwCrew;
use Modules\Crew\Entities\CrwCrewAssignment;
use Modules\Crew\Entities\CrwCrewDocument;
use Modules\Crew\Entities\CrwCrewDocumentRenewal;
use Modules\Crew\Entities\CrwCrewProfile;
use Modules\Crew\Entities\CrwRank;
use Modules\Crew\Entities\CrwRecruitmentApproval;

class CrwCommonController extends Controller
{

    /**
     * @param Request $request
     */
    public function getCrewRanks(Request $request)
    {
        try {

            $crwRanks = CrwRank::when(request()->business_unit != 'ALL', function ($q)
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

            $crwAgencies = CrwAgency::when(request()->business_unit != 'ALL', function ($q)
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

    /**
     * @return mixed
     */
    public function getCrewAgencyContracts()
    {
        try {

            $crwAgencies = CrwAgencyContract::
//            when(request()->business_unit != "ALL", function ($q)
                //            {
                //                $q->where('business_unit', request()->business_unit);
                //            })
                //            ->
                when(request()->crw_agency_id != null, function ($q)
            {
                return $q->where('crw_agency_id', request()->crw_agency_id);
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

            $crwAgencies = CrwRecruitmentApproval::when(request()->business_unit != 'ALL', function ($q)
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

            $crewProfiles = CrwCrewProfile::when(request()->business_unit != 'ALL', function ($q)
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

    /**
     * @param Request $request
     */
    public function getCrewDocuments(Request $request)
    {
        try {
            $crwAgencies = CrwCrewDocument::with(['crwCrewDocumentRenewals' => function ($q)
            {
                $q->orderBy('issue_date', 'DESC');
            }])
                ->where('crw_crew_profile_id', $request->crw_crew_profile_id)
                ->when(request()->business_unit != 'ALL', function ($q)
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

    /**
     * @param Request $request
     */
    public function getCrewDocumentRenewals(Request $request)
    {

        try {
            $crwDocumentRenewals = CrwCrewDocumentRenewal::where('crw_crew_document_id', $request->crw_crew_document_id)
                ->orderBy('issue_date', 'DESC')
                ->get();

            return response()->success('Retrieved Successfully', $crwDocumentRenewals, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     */
    public function getVesselAssignedCrews(Request $request)
    {

        try {
            $vesselAssignedCrews = CrwCrewAssignment::with('crwCrew:id,full_name,pre_mobile_no')
                ->where('ops_vessel_id', $request->ops_vessel_id)
                ->where('status', 'Onboard')
                ->get();

            return response()->success('Retrieved Successfully', $vesselAssignedCrews, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVesselMonthlyAttendances(Request $request)
    {
        try {
            $vesselMonthlyAttendances = CrwAttendance::where('ops_vessel_id', $request->ops_vessel_id)
            ->doesntHave('crwPayrollBatch')
            ->orderBy('year_month')
            ->get(); 

            return response()->success('Retrieved Successfully', $vesselMonthlyAttendances, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }    

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCrewMonthlyAttendances(Request $request)
    {
        try {
            $attendance = CrwAttendance::with('crwAttendanceLines.crwCrew:id,full_name,pre_mobile_no', 'crwAttendanceLines.crwSalaryStructure')
                ->whereId($request->crw_attendance_id)
                ->first();

            // return $attendance;

            $attendanceData = [
                'id'                 => $attendance->id,
                'ops_vessel_id'      => $attendance->ops_vessel_id,
                'year_month'         => $attendance->year_month,
                'working_days'       => $attendance->working_days,
                'total_crews'        => $attendance->total_crews,
                'crwAttendanceLines' => $attendance->crwAttendanceLines->map(function ($attendanceLine) use ($attendance)
                {
                    $perDaySalary    = ($attendanceLine->crwSalaryStructure->net_amount / $attendance->working_days); 
                    $payableAmount = number_format((float)$attendanceLine->payable_days * $perDaySalary, 2, '.', '');

                    $attendanceLine = [
                        'id'                        => $attendanceLine->id,
                        'crw_attendance_line_id'    => $attendanceLine->id,
                        'crw_salary_structure_id'   => $attendanceLine->crwSalaryStructure->id,
                        'crw_crew_id'               => $attendanceLine->crwCrew->id,
                        'crw_full_name'             => $attendanceLine->crwCrew->full_name,
                        'crw_contact_no'            => $attendanceLine->crwCrew->pre_mobile_no,
                        'net_salary'                => $attendanceLine->crwSalaryStructure->net_amount,
                        'present_days'              => $attendanceLine->present_days,
                        'absent_days'               => $attendanceLine->absent_days,
                        'payable_days'              => $attendanceLine->payable_days,
                        'payable_amount'            => $payableAmount,
                        'total_earnings'            => 0.00,
                        'total_deductions'          => 0.00,
                        'net_payable_amount'        => $payableAmount,
                    ];

                    return $attendanceLine;
                }),
            ];

            return response()->success('Retrieved Successfully', $attendanceData, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     */
    public function getAppraisalUndoneAssignments(Request $request)
    {
        try {
            $appraisalUndoneAssignments = CrwCrewAssignment::with('opsVessel:id,name')
            ->where('crw_crew_id', $request->crw_crew_profile_id)
            ->doesntHave('appraisalRecord')
            ->where('status', 'Complete')
            ->orWhere('id', $request->crw_crew_assignment_id) //take existing one also 
            ->get();

            return response()->success('Retrieved Successfully', $appraisalUndoneAssignments, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    
    public function getAppraisalForms()
    {
        try {
            $appraisalForms = AppraisalForm::when(request()->business_unit != 'ALL', function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })
            ->get();
             
            return response()->success('Retrieved succesfully', $appraisalForms, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }    

}
