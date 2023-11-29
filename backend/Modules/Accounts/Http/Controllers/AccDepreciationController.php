<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccDepreciation;
use Modules\Accounts\Http\Requests\AccDepreciationRequest;

class AccDepreciationController extends Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $accDepreciations = AccDepreciation::with('costCenter')
                ->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accDepreciations, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccDepreciationRequest $request
     */
    public function store(AccDepreciationRequest $request)
    {
        try {
            $accDepreciationData = $request->only('month_year', 'applied_date', 'acc_cost_center_id', 'business_unit');

            DB::transaction(function () use ($request, $accDepreciationData)
            {
                $accDepreciation = AccDepreciation::create($accDepreciationData);
                $accDepreciation->accDepreciationLines()->createMany($request->accDepreciationLines);
            });

            return response()->success('Created Successfully', '', 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccDepreciation $accDepreciation
     */
    public function show(AccDepreciation $accDepreciation)
    {
        try {
            return response()->success('Retrieved Successfully', $accDepreciation->load('costCenter', 'accDepreciationLines.accFixedAsset'), 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccDepreciationRequest $request
     * @param AccDepreciation $accDepreciation
     */
    public function update(AccDepreciationRequest $request, AccDepreciation $accDepreciation)
    {
        try {
            $accDepreciationData = $request->only('month_year', 'applied_date', 'acc_cost_center_id', 'business_unit');

            DB::transaction(function () use ($request, $accDepreciationData, $accDepreciation)
            {
                $accDepreciation->update($accDepreciationData);
                $accDepreciation->accDepreciationLines()->delete();
                $accDepreciation->accDepreciationLines()->createMany($request->accDepreciationLines);
            });

            return response()->success('Updated Successfully', '', 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccDepreciation $accDepreciation
     */
    public function destroy(AccDepreciation $accDepreciation)
    {
        try {
            $accDepreciation->delete();

            return response()->success('Deleted Successfully', null, 204);

        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
