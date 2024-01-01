<?php

namespace Modules\Maintenance\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Maintenance\Entities\MntSurveyEntry;
use Modules\Maintenance\Entities\MntSurveyItem;
use Modules\Maintenance\Http\Requests\MntSurveyEntryRequest;

class MntSurveyEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $surveyEntries = MntSurveyEntry::with(["opsVessel","mntSurvey.mntSurveyItem","mntSurvey.mntSurveyType"])
                                ->globalSearch($request->all());

            return response()->success('Survey entries are retrieved successfully', $surveyEntries, 200);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('maintenance::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MntSurveyEntryRequest $request)
    {
        try {
            $input = $request->all();

            $surveyEntry = MntSurveyEntry::create($input);

            return response()->success('Survey entry created successfully', $surveyEntry, 201);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {

            $survey = MntSurveyEntry::with(["opsVessel","mntSurvey.mntSurveyItem","mntSurvey.mntSurveyType.mntSurveys"])->find($id);

            return response()->success('Survey entry type found successfully', $survey, 200);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('maintenance::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(MntSurveyEntryRequest $request, $id)
    {
        try {
            $input = $request->all();

            $survey = MntSurveyEntry::findorfail($id);
            $survey->update($input);

            return response()->success('Survey entry updated successfully', $survey, 202);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(MntSurveyEntry $surveyEntry)
    {
        try {            
            DB::beginTransaction();
            $surveyEntry->delete();
            DB::commit();
            return response()->success('Survey entry deleted successfully', $surveyEntry, 204);
            
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($surveyEntry->preventDeletionIfRelated(), 422);

        }
    }

    public function getSurveyEntries()
    {
        try {
            $opsVesselId = request()->ops_vessel_id;

            $surveyEntries = MntSurveyEntry::with(["opsVessel","mntSurvey.mntSurveyItem","mntSurvey.mntSurveyType"])
                                ->get();

            $surveyItems = MntSurveyItem::with(["mntSurveys" => function($query) use ($opsVesselId) {
                                    // Filter mntSurveys which have atleast one mnt survey entry for the given vessel
                                    $query->whereHas('mntSurveyEntries', function($q) use ($opsVesselId) {
                                        $q->where('ops_vessel_id', $opsVesselId)
                                            ->select('mnt_survey_id', DB::raw('count(mnt_survey_id) as total_items'))
                                            ->groupBy('mnt_survey_id')
                                            ->havingRaw('count(mnt_survey_id) > ?', [0]);   
                                        });
                                    }, "mntSurveys.mntSurveyEntries" => function ($q) use ($opsVesselId) {
                                        // Select only the latest entry for the given vessel
                                            $q->whereIn('id',function ($query) use ($opsVesselId) {
                                                $query->from('mnt_survey_entries')
                                                    ->where('ops_vessel_id', $opsVesselId)
                                                    ->select(DB::raw("MAX(id)"))
                                                    ->groupBy(['ops_vessel_id','mnt_survey_id']);
                                            });
                                    }])
                                    ->whereHas('mntSurveys', function($q) use ($opsVesselId) {
                                        // Filter items which have surveys which have at least one mnt survey entry
                                        $q->whereHas('mntSurveyEntries', function($q) use ($opsVesselId) {
                                            $q->where('ops_vessel_id', $opsVesselId)
                                                ->select('mnt_survey_id', DB::raw('count(mnt_survey_id) as total_items'))
                                                ->groupBy('mnt_survey_id')
                                                ->havingRaw('count(mnt_survey_id) > ?', [0]);   
                                            });
                                    })
                                    ->get();

            return response()->success('Survey item entries are retrieved successfully', $surveyItems, 200);

        }
        catch (\Exception $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
