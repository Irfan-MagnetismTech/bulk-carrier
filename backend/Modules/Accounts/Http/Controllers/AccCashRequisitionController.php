<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccCashRequisition;
use Modules\Accounts\Http\Requests\AccCashRequisitionRequest;

class AccCashRequisitionController extends Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $accCashRequisitions = AccCashRequisition::with('accCashRequisitionLines', 'costCenter')
                ->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $accCashRequisitions, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisitionRequest $request
     */
    public function store(AccCashRequisitionRequest $request)
    {
        try {
            $accCashRequisitionData = $request->only('acc_cost_center_id', 'applied_date', 'requisitor_id', 'mpr_id', 'total_amount', 'purpose', 'business_unit');
            $accCashRequisitionData['requisitor_id'] = auth()->id();

            DB::transaction(function () use ($request, $accCashRequisitionData)
            {
                $accCashRequisition = AccCashRequisition::create($accCashRequisitionData);
                $accCashRequisition->accCashRequisitionLines()->createMany($request->accCashRequisitionLines);
            });

            return response()->success('Created Successfully', '', 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisition $accCashRequisition
     */
    public function show(AccCashRequisition $accCashRequisition)
    {
        try {
            return response()->success('Retrieved Succesfully', $accCashRequisition->load('accCashRequisitionLines', 'costCenter'), 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisitionRequest $request
     * @param AccCashRequisition $accCashRequisition
     */
    public function update(AccCashRequisitionRequest $request, AccCashRequisition $accCashRequisition)
    {
        try {
            $accCashRequisitionData = $request->only('acc_cost_center_id', 'applied_date', 'requisitor_id', 'mpr_id', 'total_amount', 'purpose', 'business_unit');

            DB::transaction(function () use ($request, $accCashRequisitionData, $accCashRequisition)
            {
                $accCashRequisition->update($accCashRequisitionData);
                $accCashRequisition->accCashRequisitionLines()->delete();
                $accCashRequisition->accCashRequisitionLines()->createMany($request->accCashRequisitionLines);
            });

            return response()->success('Updated Successfully', '', 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisition $accCashRequisition
     */
    public function destroy(AccCashRequisition $accCashRequisition)
    {
        try {
            $accCashRequisition->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
