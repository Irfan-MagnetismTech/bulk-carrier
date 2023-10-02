<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewRequisition;

class CrwRequisitionController extends Controller
{

    public function index()
    {
        try {
            $crwCrewRequisitions = CrwCrewRequisition::with('crwCrewRequisitionLines')->get();

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


    public function show(CrwCrewRequisition $crwCrewRequisition)
    {
        try {
            return response()->success('Retrieved succesfully', $crwCrewRequisition->load('crwCrewRequisitionLines'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, CrwCrewRequisition $crwCrewRequisition)
    {
        try {
            DB::transaction(function () use ($request, $crwCrewRequisition)
            {
                $crwRequisitionData = $request->only('ops_vessel_id', 'applied_date', 'total_required_manpower', 'remarks', 'business_unit');
                $crwCrewRequisition->update($crwRequisitionData);
                $crwCrewRequisition->crwCrewRequisitionLines()->delete();
                $crwCrewRequisition->crwCrewRequisitionLines()->createMany($request->crwCrewRequisitionLines);

                return response()->success('Updated succesfully', $crwCrewRequisition, 202);
            });
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function destroy(CrwCrewRequisition $crwCrewRequisition)
    {
        try {
            $crwCrewRequisition->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
