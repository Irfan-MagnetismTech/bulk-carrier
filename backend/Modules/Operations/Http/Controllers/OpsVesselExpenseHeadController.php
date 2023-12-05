<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVessel;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Http\Requests\OpsVesselExpenseHeadRequest;

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
            ->globalSearch($request->all())
            ->groupBy('vessel_code');


            return response()->success('Data retrieved successfully.', $vessel_expense_heads, 200);
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
        // dd($request);
        try {
            // DB::beginTransaction();

            $ops_vessel_id = $request->ops_vessel_id;
            OpsVesselExpenseHead::where('ops_vessel_id', $ops_vessel_id)
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->delete();

            $items = collect(collect($request->heads)->unique()->values()->all())->map(function($item) use($ops_vessel_id) {
                return [
                    'ops_vessel_id' => $ops_vessel_id,
                    'ops_expense_head_id' => $item
                ];
            })->toArray();

            // dd($items);

            // OpsVesselExpenseHead::insert($items);

            // $vessel_expense_head = OpsVesselExpenseHead::with(['opsExpenseHead' => function($query){
            //     $query->select('id', 'name');
            // }])
            // ->where('ops_vessel_id', $ops_vessel_id)
            // ->get();
            
            // DB::commit();

            $insertables = [];

            $heads = collect($request->heads);

            $heads = $heads->map(function($head) use(&$insertables, $ops_vessel_id) {
                if($head['is_checked']) {
                    array_push($insertables, 
                    [
                        
                        'ops_vessel_id' => $ops_vessel_id,
                        'ops_expense_head_id' => $head['id']
                    ]
                );
                }
                collect($head['opsSubHeads'])->map(function($sub) use(&$insertables, $ops_vessel_id) {
                    if($sub['is_checked']) {
                        array_push($insertables, 
                        [
                        
                            'ops_vessel_id' => $ops_vessel_id,
                            'ops_expense_head_id' => $sub['id']
                        ]);
                    }
                });
            });

            OpsVesselExpenseHead::insert($insertables);


            return response()->success('Data added Successfully.', $insertables, 201);
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
            $vessel_expense_heads= OpsVesselExpenseHead::where('vessel_code', $vessel_expense_head->vessel_code)->with(['opsExpenseHead' => function($query){
                $query->select('id', 'name');
            }])->get()
            ->groupBy('vessel_code');

            // $heads = OpsVesselExpenseHead::where('vessel_code', $vessel_expense_head->vessel_code)->pluck('ops_expense_head_id');
            // $vesselDetails = OpsVessel::where('short_code', $vessel_expense_head->vessel_code)->first();

            // $info = [
            //     'vessel_code' => $vessel_expense_head->vessel_code,
            //     'vessel_details' => $vesselDetails,
            //     'heads' => $heads
            // ];
            
            return response()->success('Data retrieved successfully.', $vessel_expense_heads, 200);
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
    // public function update(OpsVesselExpenseHeadRequest $request, OpsVesselExpenseHead $vessel_expense_head): JsonResponse
    // {
    //     // dd($request);
    //     try {
    //         DB::beginTransaction();
    //         $vessel_expense_head->update($request->all());
    //         DB::commit();
    //         return response()->success('Vessel expense heads updated Successfully.', $vessel_expense_head, 202);
    //     }
    //     catch (QueryException $e)
    //     {
    //         DB::rollBack();
    //         return response()->error($e->getMessage(), 500);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsVesselExpenseHead  $vessel_expense_head
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsVesselExpenseHead $vessel_expense_head): JsonResponse
    {
        try{
            OpsVesselExpenseHead::where('vessel_code', $vessel_expense_head->vessel_code)->delete();

            return response()->json([
                'message' => 'Data deleted successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVesselExpenseHeadByVessel(Request $request){
        try {
            $vessel_expense_heads = OpsVesselExpenseHead::query()
            ->with(['opsExpenseHead' => function($query){
                $query->select('id', 'name');
            }])
            ->where(function ($query) use($request) {
                $query->where('vessel_code', 'like', '%' . $request->vessel_code . '%');
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->get()
            ->groupBy('vessel_code');

            return response()->success('Data retrieved successfully.', $vessel_expense_heads, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
