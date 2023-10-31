<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCostCenter;

class AccCostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accCostCenters = AccCostCenter::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $accCostCenters, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
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
            $costCenterData = $request->only('name', 'short_name', 'business_unit', 'type');
            $AccCostCenter     = AccCostCenter::create($costCenterData);

            return response()->json([
                'status' => 'success',
                'value'  => $AccCostCenter,
            ], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccCostCenter  $AccCostCenter
     * @return \Illuminate\Http\Response
     */
    public function show(AccCostCenter $AccCostCenter)
    {
        try {
            return response()->json([
                'status' => 'success',
                'value'  => $AccCostCenter,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccCostCenter  $AccCostCenter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccCostCenter $AccCostCenter)
    {
        try {
            $costCenterData = $request->only('name', 'short_name', 'business_unit', 'type');
            $AccCostCenter->update($costCenterData);

            return response()->json([
                'status' => 'success',
                'value'  => $AccCostCenter,
            ], 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccCostCenter  $AccCostCenter
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccCostCenter $AccCostCenter)
    {
        try {
            $AccCostCenter->delete();

            return response()->json([
                'status' => 'success',
                'value'  => null,
            ], 204);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
