<?php

namespace Modules\Crew\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewAssignment;
use Modules\Crew\Entities\CrwCrewProfile;
use Modules\Crew\Entities\CrwRestHourEntry;
use Modules\Crew\Http\Requests\CrwRestHourEntryRequest;
use Modules\Operations\Entities\OpsVessel;

class CrwRestHourEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:crw-rest-hour-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-rest-hour-create', ['only' => ['store']]);
        $this->middleware('permission:crw-rest-hour-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-rest-hour-delete', ['only' => ['destroy']]);
        $this->middleware('permission:crw-rest-hour-record', ['only' => ['crwRestHourReport']]);
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwRestHourEntries = CrwRestHourEntry::with('opsVessel:id,name', 'crwRestHourEntryLines')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwRestHourEntries, 200);
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
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwRestHourEntryData = $request->only('ops_vessel_id', 'record_date', 'business_unit');

                $crwRestHourEntryLines = collect($request->selectedCrews)->map(function ($selectedCrew) use ($request)
                {
                    $workHours                                   = count($request->hourlyRecords);
                    $selectedCrew['hourly_records']              = $request->hourlyRecords;
                    $selectedCrew['work_hours']                  = ($workHours * 30) / 60;
                    $selectedCrew['rest_hours']                  = 24 - $workHours;
                    $selectedCrew['overtime_hours']              = $workHours > 8 ? ($workHours - 8) : 0.00;
                    $selectedCrew['applicable_rest_hour_daily']  = 14;
                    $selectedCrew['applicable_rest_hour_weekly'] = 164;

                    return $selectedCrew;
                })->all();

                $crwRestHourEntry = CrwRestHourEntry::create($crwRestHourEntryData);
                $crwRestHourEntry->crwRestHourEntryLines()->createMany($crwRestHourEntryLines);
            });

            return response()->success('Created Succesfully', [], 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwRestHourEntry  $crwRestHourEntry
     * @return \Illuminate\Http\Response
     */
    public function show(CrwRestHourEntry $crwRestHourEntry)
    {
        try {
            return response()->success('Retrieved succesfully',
                $crwRestHourEntry->load('opsVessel:id,name', 'crwRestHourEntryLines.crwCrewProfile:id,full_name,pre_mobile_no,crw_rank_id', 'crwRestHourEntryLines.crwCrewAssignment'), 200);
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
     * @param  \App\Models\CrwRestHourEntry  $crwRestHourEntry
     * @return \Illuminate\Http\Response
     */
    public function update(CrwRestHourEntryRequest $request, CrwRestHourEntry $crwRestHourEntry)
    {
        try {
            DB::transaction(function () use ($request, $crwRestHourEntry)
            {
                $crwRestHourEntryData = $request->only('ops_vessel_id', 'record_date', 'business_unit');
                $crwRestHourEntry->update($crwRestHourEntryData);
                $crwRestHourEntry->crwRestHourEntryLines()->delete();
                $crwRestHourEntry->crwRestHourEntryLines()->createMany($request->crwRestHourEntryLines);
            });

            return response()->success('Updated succesfully', $crwRestHourEntry, 202);

        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwRestHourEntry  $crwRestHourEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwRestHourEntry $crwRestHourEntry)
    {
        try {
            $crwRestHourEntry->delete();

            return response()->success('Deleted Succesfully', null, 204);
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
    public function crwRestHourReport(Request $request)
    {
        try {
            $crewId       = $request->crw_crew_id;
            $vesselId     = $request->ops_vessel_id;
            $from         = Carbon::parse($request->year_month)->startOfMonth()->format('Y-m-d');
            $till         = Carbon::parse($request->year_month)->endOfMonth()->format('Y-m-d');
            $dailyRecords = CrwRestHourEntry::where('ops_vessel_id', $vesselId)->whereBetween('record_date', [$from, $till])
                ->with(['crwRestHourEntryLine' => fn($q) => $q->where('crw_crew_id', $crewId)])->get();

            if(count($dailyRecords) < 1){
                return response()->json([
                    'value' => '',
                    'message' => 'No data found'
                ],422);
            }
            $crwCrewAssignmentId = $dailyRecords->first()->crwRestHourEntryLine->crw_crew_assignment_id;

            $restHourRecords                       = [];
            $restHourRecords['crw_profile']        = CrwCrewProfile::with('crwCurrentRank')->where('id', $crewId)->first(['id', 'full_name', 'pre_mobile_no']);
            $restHourRecords['vessel']             = OpsVessel::where('id', $vesselId)->first(['vessel_type', 'name', 'short_code', 'flag', 'imo']);
            $restHourRecords['assignment']         = CrwCrewAssignment::where('id', $crwCrewAssignmentId)->first();
            $restHourRecords['ttl_work_hours']     = $dailyRecords->pluck('crwRestHourEntryLine')->sum('work_hours');
            $restHourRecords['ttl_rest_hours']     = $dailyRecords->pluck('crwRestHourEntryLine')->sum('rest_hours');
            $restHourRecords['ttl_overtime_hours'] = $dailyRecords->pluck('crwRestHourEntryLine')->sum('overtime_hours');
            $restHourRecords['fixed_overtime']     = 0;
            $restHourRecords['extra_overtime']     = $restHourRecords['ttl_overtime_hours'] > $restHourRecords['fixed_overtime'] ?  $restHourRecords['ttl_overtime_hours'] - $restHourRecords['fixed_overtime'] : 0.00;

            $restHourRecords['daily_records'] = $dailyRecords->map(function ($q)
            {
                $q->day = Carbon::parse($q->record_date)->day;

                return $q;
            });

            return response()->success('Retrieved Succesfully', $restHourRecords, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }
}
