<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCostCenter;
use Modules\Accounts\Http\Requests\AccCostCenterRequest;
use Spatie\Permission\Traits\HasRoles;


class AccCostCenterController extends Controller
{
    use HasRoles; 

    public function __construct()
    {
        $this->middleware('permission:acc-cost-center-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:acc-cost-center-create', ['only' => ['store']]);
        $this->middleware('permission:acc-cost-center-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:acc-cost-center-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $accCostCenters = AccCostCenter::all();
            // $accCostCenters = AccCostCenter::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accCostCenters, 200);
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
    public function store(AccCostCenterRequest $request) : JsonResponse
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
    public function update(AccCostCenterRequest $request, AccCostCenter $AccCostCenter)
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
