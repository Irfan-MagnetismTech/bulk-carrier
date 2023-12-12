<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVoyageBudget;
use Modules\Operations\Http\Requests\OpsVoyageBudgetRequest;

class OpsVoyageBudgetController extends Controller
{
    // use HasRoles;

    //    function __construct(private FileUploadService $fileUpload)
    //    {
    //     $this->middleware('permission:voyage-budget-create|voyage-budget-edit|voyage-budget-show|voyage-budget-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:voyage-budget-create', ['only' => ['store']]);
    //     $this->middleware('permission:voyage-budget-edit', ['only' => ['update']]);
    //     $this->middleware('permission:voyage-budget-delete', ['only' => ['destroy']]);
    //    }
    /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $voyage_budgets = OpsVoyageBudget::with('opsVessel','opsVoyage','opsExpenseHead','opsVoyageBudgetEntries.opsExpenseHead')->globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $voyage_budgets, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
    * 
    * @param OpsVoyageBudgetRequest $request
    * @return JsonResponse
    */
    public function store(OpsVoyageBudgetRequest $request): JsonResponse
    {

        $budgetLines = [];

        collect($request->heads)->map(function($head) use(&$budgetLines) {

            if($head['opsSubHeads']) {
                collect($head['opsSubHeads'])->map(function($subHead) use(&$budgetLines) {
                    array_push($budgetLines, $subHead);
                });
            } else {
                array_push($budgetLines, $head);
            }
        });

        // return response()->json($budgetLines);
        try {
            DB::beginTransaction();
            $voyage_budget_info = $request->except(
                '_token',
                'opsVoyageBudgetEntries',
            );

            $voyage_budget = OpsVoyageBudget::create($voyage_budget_info);
            $voyage_budget->opsVoyageBudgetEntries()->createMany($budgetLines);
            DB::commit();
            return response()->success('Data added successfully.', $voyage_budget, 201);
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
    * @param  OpsVoyageBudget  $voyage_budget
    * @return JsonResponse
    */
    public function show(OpsVoyageBudget $voyage_budget): JsonResponse
    {
        $voyage_budget->load('opsVessel','opsVoyage','opsExpenseHead','opsVoyageBudgetEntries.opsExpenseHead');
        try
        {
            return response()->success('Data retrieved successfully.', $voyage_budget, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


    /**
     * Update the specified resource in storage.
    *
    * @param OpsVoyageBudgetRequest $request
    * @param  OpsVoyageBudget  $voyage_budget
    * @return JsonResponse
    */
    public function update(OpsVoyageBudgetRequest $request, OpsVoyageBudget $voyage_budget): JsonResponse
    {
        try {
            DB::beginTransaction();

            $voyage_budget_info = $request->except(
                '_token',
                'opsVoyageBudgetEntries',
            );
            
            $voyage_budget->update($voyage_budget_info);
            $voyage_budget->opsVoyageBudgetEntries()->createUpdateOrDelete($request->opsVoyageBudgetEntries);
            DB::commit();
            return response()->success('Data updated successfully.', $voyage_budget, 202);
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
    * @param  OpsVoyageBudget  $voyage_budget
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(OpsVoyageBudget $voyage_budget): JsonResponse
    {
        try
        {
            $voyage_budget->opsVoyageBudgetEntries()->delete();
            $voyage_budget->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVoyageBudgetByTitle(Request $request){
        try {
            $voyage_budgets = OpsVoyageBudget::query()
            ->when(isset(request()->title) , function($query){
                $query->where('title', 'like', '%' . request()->title . '%');
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->get();

            return response()->success('Data retrieved successfully.', $voyage_budgets, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
