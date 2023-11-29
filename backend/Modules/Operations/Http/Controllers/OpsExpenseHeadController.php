<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Http\Requests\OpsExpenseHeadRequest;

class OpsExpenseHeadController extends Controller
{
    // use HasRoles;
    
    
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:expense-head-create|expense-head-edit|expense-head-show|expense-head-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:expense-head-create', ['only' => ['store']]);
   //     $this->middleware('permission:expense-head-edit', ['only' => ['update']]);
   //     $this->middleware('permission:expense-head-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $expenseHeads = OpsExpenseHead::whereNull('head_id')
            ->with('opsSubHeads')
            ->latest()->paginate(15);

            return response()->success('Successfully retrieved expense heads.', $expenseHeads, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsExpenseHeadRequest $request
      * @return JsonResponse
     */
     public function store(OpsExpenseHeadRequest $request): JsonResponse
     {
        try {
            DB::beginTransaction();

            $actualSubHeads = collect($request->opsSubHeads)->map(function ($item, $key) {
                if(!empty($item['name'])) {
                    return $item;
                }
            })->filter()->toArray();
            
            $head = OpsExpenseHead::create(['name' => $request->name,'business_unit'=> $request ->business_unit, 'is_visible_in_voyage_report' => $request->is_visible_in_voyage_report]);

            if(!empty($actualSubHeads)) {
                $subHeads = collect($request->opsSubHeads)->map(function($item, $key) use ($head)
                {
                    return [
                        'name' => $item['name'],
                        'billing_type' => (isset($item['billing_type'])) ? $item['billing_type'] : null,
                        'head_id'   =>  $head->id,
                        'business_unit'=>request()->business_unit, 
                        'is_visible_in_voyage_report'    => $head->is_visible_in_voyage_report
                    ];
                })->toArray();

                OpsExpenseHead::insert($subHeads);
            }
            DB::commit();
            return response()->success('Expense head added successfully.', $head, 201);
        }
            catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
     }
 
     /**
      * Display the specified maritime certification.
      *
      * @param  OpsExpenseHead  $expense_head
      * @return JsonResponse
      */
     public function show(OpsExpenseHead $expense_head): JsonResponse
     {        
         try
         {
             return response()->success('Successfully retrieved expense head.', $expense_head->load('opsSubHeads'), 200);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
 
     }
 
 
    /**
     * Update the specified resource in storage.
    *
    * @param OpsExpenseHeadRequest $request
    * @param  OpsExpenseHead  $expense_head
    * @return JsonResponse
    */
    public function update(OpsExpenseHeadRequest $request, OpsExpenseHead $expense_head): JsonResponse
    {
        try {
        $expense_head->update(['name' => $request->name, 'is_visible_in_voyage_report' => $request->is_visible_in_voyage_report]);

        $actualSubHeads = collect($request->opsSubHeads)->map(function ($item, $key) {
            if(!empty($item['name'])) {
                return $item;
            }
        })->filter()->toArray();

        if(!empty($actualSubHeads)) {

            $subHeads = collect($request->opsSubHeads)->whereNotNull('head_id')->map(function($item, $key) use ($request, $expense_head)
            {
                return [
                    'id' => $item['id'],
                    'head_id' => $expense_head->id,
                    'name' => $item['name'],
                    'billing_type' => (isset($item['billing_type'])) ? $item['billing_type'] : null,
                    'is_visible_in_voyage_report' => $request->is_visible_in_voyage_report ?? null
                ];
            })->toArray();

            $newHeads = collect($request->opsSubHeads)->whereNull('head_id')->map(function($item, $key) use ($expense_head) {
                return [
                    'head_id' => $expense_head->id,
                    'name' => $item['name'],
                    'billing_type' => (isset($item['billing_type'])) ? $item['billing_type'] : null,
                ];
            })->toArray();

            $oldIds = $expense_head->opsSubHeads->pluck('id')->toArray();
            $newIds = collect($subHeads)->pluck('id')->toArray();

            $deletable = array_diff($oldIds, $newIds);

            $fields = ['head_id', 'name', 'billing_type', 'is_visible_in_voyage_report'];

            DB::beginTransaction();
            OpsExpenseHead::whereIn('id', $deletable)->delete();
            OpsExpenseHead::insert($newHeads);
            OpsExpenseHead::upsert($subHeads, ['id'], $fields);
            DB::commit();
        }
        return response()->success('Expense head updated successfully.', $expense_head, 200);
        }
        catch (QueryException $e)
        {            
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
 
    /**
     * Remove the specified vessel from storage.
    *
    * @param  OpsExpenseHead  $expense_head
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(OpsExpenseHead $expense_head): JsonResponse
    {
        try
        {
            $expense_head->delete();

            return response()->json([
                'message' => 'Successfully deleted expense head.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getExpenseHeadByHead(Request $request){
        try {
            $heads = OpsExpenseHead::with('opsHeads', 'opsSubHeads')
            ->where('id', $request->head_id)->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->get()->toArray();
                
            $globals = OpsExpenseHead::with('opsHeads', 'opsSubHeads')
            ->where('is_visible_in_voyage_report', 1)
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->get()->toArray();

            $output = collect(array_merge($heads, $globals));

            $heads = $output->map(function($item, $key) {
                if(($item['head_id'] == null) && (count($item['opsSubHeads']) > 0)) {
                } else {
                    return $item;
                }
            });

            $result = collect($heads)->map(function($item, $key) {
                if(!empty($item)) {
                    $item['category_head'] = $item['name'].((count($item['opsHeads']) > 0) ? " - ".collect($item['opsHeads'])->first()['name'] : null);
                    return $item;
                }                
            })->filter()->values();

            return response()->success('Successfully retrieved expense head.', $result, 200);

        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    
    public function getSubHead($headId) {
        try {
            $subheads = OpsExpenseHead::where('head_id', $headId)->get();
            return response()->json([
                'value' => $subheads,
                'message' => 'Expense Subheads derived successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}