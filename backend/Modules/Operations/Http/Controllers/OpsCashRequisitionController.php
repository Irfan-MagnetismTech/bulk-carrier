<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsCashRequisition;
use Modules\Operations\Http\Requests\OpsCashRequisitionRequest;

class OpsCashRequisitionController extends Controller
{
   // use HasRoles;
    // function __construct()
    // {
    //     $this->middleware('permission:cash-requisition-create|cash-requisition-edit|cash-requisition-show|cash-requisition-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:cash-requisition-create', ['only' => ['store']]);
    //     $this->middleware('permission:cash-requisition-edit', ['only' => ['update']]);
    //     $this->middleware('permission:cash-requisition-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $cash_requisitions = OpsCashRequisition::globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $cash_requisitions, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param OpsCashRequisitionRequest $request
     * @return JsonResponse
     */
    public function store(OpsCashRequisitionRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $cash_requisition = OpsCashRequisition::create($request->all());
            DB::commit();
            return response()->success('Data added successfully.', $cash_requisition, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified port.
     *
     * @param  OpsCashRequisition  $cash_requisition
     * @return JsonResponse
     */
    public function show(OpsCashRequisition $cash_requisition): JsonResponse
    {
        try {
            return response()->success('Data retrieved successfully.', $cash_requisition, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsCashRequisitionRequest  $request
     * @param  OpsCashRequisition  $cash_requisition
     * @return JsonResponse
     */
    public function update(OpsCashRequisitionRequest $request, OpsCashRequisition $cash_requisition): JsonResponse
    {
        try {
            DB::beginTransaction();
            $cash_requisition->update($request->all());
            DB::commit();
            return response()->success('Data updated successfully.', $cash_requisition, 202);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsCashRequisition  $cash_requisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsCashRequisition $cash_requisition): JsonResponse
    {
        try {
            $cash_requisition->delete($cash_requisition);

            return response()->json([
                'message' => 'Data deleted successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCashRequisitionBySerial(Request $request){
        try {
            $cash_requisitions = OpsCashRequisition::query()
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })            
            ->where(function ($query) use($request) {
                $query->where('serial', 'like', '%' . $request->serial . '%');
            })
            ->limit(10)
            ->get();

            return response()->success('Data retrieved successfully.', $cash_requisitions, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCashRequisitionApproved(Request $request){
        try {
            $cash_requisitions = OpsCashRequisition::query()
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })   
            ->where(function ($query) use($request) {
                $query->where('serial', 'like', '%' . $request->serial . '%');
            })
            ->where('status', 'approved')
            ->get();

            return response()->success('Data retrieved successfully.', $cash_requisitions, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}