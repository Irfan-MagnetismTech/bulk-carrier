<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsVoyageBudget;
use Modules\Operations\Http\Requests\OpsVoyageBudgetRequest;
use Illuminate\Support\Facades\DB;

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
            $voyage_budgets = OpsVoyageBudget::with('opsVessel','opsVoyage','opsVoyageBudgetEntries')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved cargo tariffs.', $voyage_budgets, 200);
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
        // dd($request);
        try {
            DB::beginTransaction();
            $voyage_budget_info = $request->except(
                '_token',
                'opsVoyageBudgetEntries',
            );

            $voyage_budget = OpsVoyageBudget::create($voyage_budget_info);
            $voyage_budget->opsVoyageBudgetEntries()->createMany($request->opsVoyageBudgetEntries);
            DB::commit();
            return response()->success('Voyage budget added successfully.', $voyage_budget, 201);
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
        $voyage_budget->load('opsVessel','opsVoyage','opsVoyageBudgetEntries');
        try
        {
            return response()->success('Successfully retrieved voyage budget.', $voyage_budget, 200);
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
            return response()->success('Voyage budget updated successfully.', $voyage_budget, 200);
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
                'message' => 'Successfully deleted voyage budget.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
