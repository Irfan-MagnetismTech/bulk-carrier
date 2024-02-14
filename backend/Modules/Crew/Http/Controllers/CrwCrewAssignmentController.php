<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crew\Entities\CrwCrewAssignment;
use Modules\Crew\Http\Requests\CrwCrewAssignmentRequest;
use Modules\SupplyChain\Services\UniqueId;

class CrwCrewAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:crw-assignment-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-assignment-create', ['only' => ['store']]);
        $this->middleware('permission:crw-assignment-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-assignment-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrewAssignments = CrwCrewAssignment::with('opsVessel:id,name','crwCrewProfile:id,full_name,pre_mobile_no,crw_rank_id', 'opsPort')
            ->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwCrewAssignments, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrwCrewAssignmentRequest $request)
    {
        try {
            $crwCrewAssignmentData = $request->only('ops_vessel_id', 'assignment_code', 'crw_crew_id', 'position_onboard', 'is_watchkeeper', 'joining_date', 'joining_port_code', 'duration', 'remarks', 'business_unit');
            $crwCrewAssignment     = CrwCrewAssignment::create($crwCrewAssignmentData);
            $assignmentCode = 'ASGMNT-'.date('Y').'-'.$crwCrewAssignment->id;
            $crwCrewAssignment->update(['assignment_code' => $assignmentCode]);

            return response()->success('Created Successfully', $crwCrewAssignment, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrewAssignment  $crwCrewAssignment
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            return response()->success('Retrieved successfully', $crwCrewAssignment->load('opsVessel','crwCrewProfile.crwCurrentRank','opsPort'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CrwCrewAssignment  $crwCrewAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(CrwCrewAssignmentRequest $request, CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            $crwCrewAssignmentData = $request->only('ops_vessel_id', 'crw_crew_id', 'position_onboard', 'is_watchkeeper', 'joining_date', 'joining_port_code', 'duration', 'remarks', 'business_unit');
            $crwCrewAssignment->update($crwCrewAssignmentData);

            return response()->success('Updated successfully', $crwCrewAssignment, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrewAssignment  $crwCrewAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            $crwCrewAssignment->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function updateCrewAssignStatus(Request $request, CrwCrewAssignment $crwCrewAssignment)
    {
        try {
            $crwCrewAssignmentData = $request->only('status', 'completion_date', 'completion_remarks');
            $crwCrewAssignment->update($crwCrewAssignmentData);

            return response()->success('Updated succesfully', $crwCrewAssignment, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
