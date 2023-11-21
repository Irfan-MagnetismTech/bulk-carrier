<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewRequisition;
use Modules\Crew\Http\Requests\CrwRequisitionRequest;

class CrwCrewRequisitionController extends Controller
{

    public function index(Request $request)
    {
        try {
            $crwCrewRequisitions = CrwCrewRequisition::with('crwCrewRequisitionLines','opsVessel:id,name')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwCrewRequisitions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function store(CrwRequisitionRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwRequisitionData = $request->only('ops_vessel_id', 'applied_date', 'total_required_manpower', 'remarks', 'business_unit');
                $crwCrewRequisition     = CrwCrewRequisition::create($crwRequisitionData);
                $crwCrewRequisition->crwCrewRequisitionLines()->createMany($request->crwCrewRequisitionLines);

                return response()->success('Created Successfully', $crwCrewRequisition, 201);
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
            return response()->success('Retrieved Successfully', $crwRequisition->load('crwCrewRequisitionLines','opsVessel'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update(CrwRequisitionRequest $request, CrwCrewRequisition $crwRequisition)
    {
        try {
            DB::transaction(function () use ($request, $crwRequisition)
            {
                $crwRequisitionData = $request->only('ops_vessel_id', 'applied_date', 'total_required_manpower', 'remarks', 'business_unit');
                $crwRequisition->update($crwRequisitionData);
                $crwRequisition->crwCrewRequisitionLines()->delete();
                $crwRequisition->crwCrewRequisitionLines()->createMany($request->crwCrewRequisitionLines);

                return response()->success('Updated Successfully', $crwRequisition, 202);
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

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
