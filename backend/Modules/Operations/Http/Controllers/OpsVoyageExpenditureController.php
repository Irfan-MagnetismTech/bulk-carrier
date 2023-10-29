<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVoyageExpenditure;
use Modules\Operations\Http\Requests\OpsVoyageExpenditureRequest;

class OpsVoyageExpenditureController extends Controller
{
   // use HasRoles;
   
    //    function __construct(private FileUploadService $fileUpload)
    //    {
    //     $this->middleware('permission:voyage-expenditure-create|voyage-expenditure-edit|voyage-expenditure-show|voyage-expenditure-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:voyage-expenditure-create', ['only' => ['store']]);
    //     $this->middleware('permission:voyage-expenditure-edit', ['only' => ['update']]);
    //     $this->middleware('permission:voyage-expenditure-delete', ['only' => ['destroy']]);
    //    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $voyage_expenditures = OpsVoyageExpenditure::with('opsVessel','opsVoyageExpenditureEntries')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved voyage expenditures.', $voyage_expenditures, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
    /**
     * Store a newly created resource in storage.
    * 
    * @param OpsVoyageExpenditureRequest $request
    * @return JsonResponse
    */
    public function store(OpsVoyageExpenditureRequest $request): JsonResponse
    {
    // dd($request);
    try {
        DB::beginTransaction();
        $voyage_expenditure_info = $request->except(
            '_token',
            'opsVoyageExpenditureEntries',
        );

        $voyage_expenditure = OpsVoyageExpenditure::create($voyage_expenditure_info);
        $voyage_expenditure->opsVoyageExpenditureEntries()->createMany($request->opsVoyageExpenditureEntries);
        DB::commit();
        return response()->success('Voyage expenditure added successfully.', $voyage_expenditure, 201);
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
    * @param  OpsVoyageExpenditure  $voyage_expenditure
    * @return JsonResponse
    */
    public function show(OpsVoyageExpenditure $voyage_expenditure): JsonResponse
    {
    $voyage_expenditure->load(['opsVoyage.opsVessel','opsVoyage.opsCargoType','opsVoyageExpenditureEntries']);
    try
    {
        return response()->success('Successfully retrieved voyage expenditure.', $voyage_expenditure, 200);
    }
    catch (QueryException $e)
    {
        return response()->error($e->getMessage(), 500);
    }

    }
 
 
    /**
     * Update the specified resource in storage.
    *
    * @param OpsVoyageExpenditureRequest $request
    * @param  OpsVoyageExpenditure  $voyage_expenditure
    * @return JsonResponse
    */
    public function update(OpsVoyageExpenditureRequest $request, OpsVoyageExpenditure $voyage_expenditure): JsonResponse
    {
    try {
        DB::beginTransaction();
        $voyage_expenditure_info = $request->except(
            '_token',
            'opsVoyageExpenditureEntries',
        );
        
        $voyage_expenditure->update($voyage_expenditure_info);$voyage_expenditure->opsVoyageExpenditureEntries()->createUpdateOrDelete($request->opsVoyageExpenditureEntries);
        DB::commit();
        return response()->success('Voyage expenditure updated successfully.', $voyage_expenditure, 200);
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
    * @param  OpsVoyageExpenditure  $voyage_expenditure
    * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(OpsVoyageExpenditure $voyage_expenditure): JsonResponse
    {
        try
        {
            $voyage_expenditure->opsVoyageExpenditureEntries()->delete();
            $voyage_expenditure->delete();

            return response()->json([
                'message' => 'Successfully deleted voyage expenditure.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    } 

    public function getCargoTariffByVoyageWise(Request $request){
        try {
            $voyage_expenditures = OpsVoyageExpenditure::query()
            ->where(function ($query) use($request) {
                $query->where('ops_voyage_id',$request->ops_voyage_id);                
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->limit(10)
            ->get();

            return response()->success('Successfully retrieved voyage expendituress name.', $voyage_expenditures, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

}
