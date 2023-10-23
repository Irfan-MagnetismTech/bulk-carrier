<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Http\Requests\OpsVesselExpenseHeadRequest;
use Illuminate\Support\Facades\DB;

class OpsVesselExpenseHeadController extends Controller
{
    // use HasRoles;   
    
    // function __construct()
    // {
    //     $this->middleware('permission:vessel-expense-head-create|vessel-expense-head-edit|vessel-expense-head-show|vessel-expense-head-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:vessel-expense-head-create', ['only' => ['store']]);
    //     $this->middleware('permission:vessel-expense-head-edit', ['only' => ['update']]);
    //     $this->middleware('permission:vessel-expense-head-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $vessel_expense_heads = OpsVesselExpenseHead::with(['opsExpenseHead' => function($query){
                $query->select('id', 'name');
            }])
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->get()
            ->groupBy('vessel');


            return response()->success('Successfully retrieved vessel expense heads.', $vessel_expense_heads, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param OpsVesselExpenseHeadRequest $request
     * @return JsonResponse
     */
    public function store(OpsVesselExpenseHeadRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $vessel = $request->vessel;
            OpsVesselExpenseHead::where('vessel', $vessel)
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->delete();

            $items = collect(collect($request->heads)->unique()->values()->all())->map(function($item) use($vessel) {
                return [
                    'vessel' => $vessel,
                    'ops_expense_head_id' => $item
                ];
            })->toArray();

            OpsVesselExpenseHead::insert($items);

            $item = OpsVesselExpenseHead::with(['opsExpenseHead' => function($query){
                $query->select('id', 'name');
            }])
            ->where('vessel', $vessel)
            ->get();
            
            DB::commit();
            return response()->success('Vessel expense heads added Successfully.', $vessel_expense_head, 201);
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
     * @param  OpsVesselExpenseHead  $vessel_expense_head
     * @return JsonResponse
     */
    public function show(OpsVesselExpenseHead $vessel_expense_head): JsonResponse
    {
        try {
            $heads = OpsVesselExpenseHead::where('vessel', $vessel_expense_head->vessel)->pluck('ops_expense_head_id');
            $vesselDetails = OpsVessel::where('code', $vessel_expense_head->vessel)->first();

            $info = [
                'vessel' => $vessel_expense_head->vessel,
                'vessel_details' => $vesselDetails,
                'heads' => $heads
            ];
            return response()->success('Successfully retrieved vessel expense heads.', $info, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsVesselExpenseHeadRequest  $request
     * @param  OpsVesselExpenseHead  $vessel_expense_head
     * @return JsonResponse
     */
    public function update(OpsVesselExpenseHeadRequest $request, OpsVesselExpenseHead $vessel_expense_head): JsonResponse
    {
        // // dd($request);
        // try {
        //     DB::beginTransaction();
        //     $vessel_expense_head->update($request->all());
        //     DB::commit();
        //     return response()->success('Vessel expense heads updated Successfully.', $vessel_expense_head, 200);
        // }
        // catch (QueryException $e)
        // {
        //     DB::rollBack();
        //     return response()->error($e->getMessage(), 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsVesselExpenseHead  $vessel_expense_head
     * @return \Illuminate\Http\Response
     */
    public function destroy($vessel): JsonResponse
    {
        try{
            OpsVesselExpenseHead::where('vessel', $vessel)->delete();

            return response()->json([
                'message' => 'Vessel expense heads deleted Successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
