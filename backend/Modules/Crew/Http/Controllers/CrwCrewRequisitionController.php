<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewRequisition;

class CrwCrewRequisitionController extends Controller
{

    public function index()
    {
        try {
            $crwCrewRequisitions = CrwCrewRequisition::with('crwCrewRequisitionLines','opsVessel:id,name')->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwCrewRequisitions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwRequisitionData = $request->only('ops_vessel_id', 'applied_date', 'total_required_manpower', 'remarks', 'business_unit');
                $crwCrewRequisition     = CrwCrewRequisition::create($crwRequisitionData);
                $crwCrewRequisition->crwCrewRequisitionLines()->createMany($request->crwCrewRequisitionLines);

                return response()->success('Created Succesfully', $crwCrewRequisition, 201);
            });
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function show(CrwCrewRequisition $crwRequisition)
    {
        try {
            return response()->success('Retrieved succesfully', $crwRequisition->load('crwCrewRequisitionLines','opsVessel'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, CrwCrewRequisition $crwRequisition)
    {
        try {
            DB::transaction(function () use ($request, $crwRequisition)
            {
                $crwRequisitionData = $request->only('ops_vessel_id', 'applied_date', 'total_required_manpower', 'remarks', 'business_unit');
                $crwRequisition->update($crwRequisitionData);
                $crwRequisition->crwCrewRequisitionLines()->delete();
                $crwRequisition->crwCrewRequisitionLines()->createMany($request->crwCrewRequisitionLines);

                return response()->success('Updated succesfully', $crwRequisition, 202);
            });
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function destroy(CrwCrewRequisition $crwRequisition)
    {
        try {
            $crwRequisition->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
