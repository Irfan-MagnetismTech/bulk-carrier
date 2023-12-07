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
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Http\Requests\OpsVesselExpenseHeadRequest;
use Illuminate\Pagination\LengthAwarePaginator;

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
            $vessel_expense_heads = OpsVesselExpenseHead::with('opsVessel')
                                    ->globalSearch($request->all(), ['unique' => 'ops_vessel_id']);

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
            DB::beginTransaction();

            $ops_vessel_id = $request->ops_vessel_id;
            OpsVesselExpenseHead::where('ops_vessel_id', $ops_vessel_id)->delete();

            $insertables = [];

            $heads = collect($request->heads);

            $heads = $heads->map(function($head) use(&$insertables, $ops_vessel_id, $request) {
                if($head['is_checked']) {
                    array_push($insertables, 
                        [
                            'ops_vessel_id' => $ops_vessel_id,
                            'business_unit' => $request->business_unit,
                            'ops_expense_head_id' => $head['id'],
                        ]
                    );
                }
                collect($head['opsSubHeads'])->map(function($sub) use(&$insertables, $ops_vessel_id, $request) {
                    if($sub['is_checked']) {
                        array_push($insertables, 
                        [
                            'ops_vessel_id' => $ops_vessel_id,
                            'business_unit' => $request->business_unit,
                            'ops_expense_head_id' => $sub['id'],
                        ]);
                    }
                });
            });

            OpsVesselExpenseHead::insert($insertables);

            DB::commit();

            return response()->success('Data added Successfully.', $insertables, (isset($request->type) ? 202 : 201));
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
            $vessel_expense_heads = OpsVesselExpenseHead::where('ops_vessel_id', $vessel_expense_head->ops_vessel_id)->with(['opsExpenseHead' => function($query){
                $query->select('id', 'name');
            }])->pluck('ops_expense_head_id')->toArray();

            // $heads = OpsVesselExpenseHead::where('vessel_code', $vessel_expense_head->vessel_code)->pluck('ops_expense_head_id');
            // $vesselDetails = OpsVessel::where('short_code', $vessel_expense_head->vessel_code)->first();

            $expenseHeads = OpsExpenseHead::with('opsSubHeads')
                    ->where('business_unit', $vessel_expense_head->business_unit)
                    ->whereNull('head_id')
                    ->get(['id', 'head_id', 'name']);

            $expenseHeads->map(function($item) use($vessel_expense_heads) {
                $item['is_checked'] = (in_array($item['id'], $vessel_expense_heads)) ? true : false;

                $item->opsSubHeads->map(function($subhead) use($vessel_expense_heads) {
                    $subhead['is_checked'] = (in_array($subhead['id'], $vessel_expense_heads)) ? true : false;
                    return $subhead;
                });

                return $item;
            });

            $data = [
                'business_unit' => $vessel_expense_head->business_unit,
                'opsVessel' => $vessel_expense_head->opsVessel,
                'ops_vessel_id' => $vessel_expense_head->opsVessel->id,
                'heads' => $expenseHeads
            ];
            
            return response()->success('Data retrieved successfully.', $data, 200);
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

    public function showVesselExpenseHeads(Request $request) {
        try {
            $vessel_expense_heads = OpsVesselExpenseHead::where('ops_vessel_id', $request->ops_vessel_id)->with(['opsExpenseHead' => function($query){
                $query->select('id', 'name');
            }])->pluck('ops_expense_head_id')->toArray();

            $expenseHeads = OpsExpenseHead::with('opsSubHeads')
                    ->whereNull('head_id')
                    ->get(['id', 'head_id', 'name']);

            $expenseHeads->map(function($item) use($vessel_expense_heads) {
                $item['is_checked'] = (in_array($item['id'], $vessel_expense_heads)) ? true : false;

                $item->opsSubHeads->map(function($subhead) use($vessel_expense_heads) {
                    $subhead['is_checked'] = (in_array($subhead['id'], $vessel_expense_heads)) ? true : false;
                    return $subhead;
                })->reject(function($item) {
                    return $item['is_checked'] == false;
                });

                return $item;
            });
            
            $data = [
                'heads' => $expenseHeads
            ];
            
            return response()->success('Data retrieved successfully.', $data, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
