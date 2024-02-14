<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVoyageExpenditure;
use Modules\Operations\Http\Requests\OpsVoyageExpenditureRequest;

class OpsVoyageExpenditureController extends Controller
{
    use HasRoles;
   
    function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:ops-voyage-expense-create|ops-voyage-expense-edit|ops-voyage-expense-view|ops-voyage-expense-delete', ['only' => ['index','show']]);
        $this->middleware('permission:ops-voyage-expense-create', ['only' => ['store']]);
        $this->middleware('permission:ops-voyage-expense-edit', ['only' => ['update']]);
        $this->middleware('permission:ops-voyage-expense-delete', ['only' => ['destroy']]);
    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $voyage_expenditures = OpsVoyageExpenditure::with('opsVoyage','opsVessel','opsVoyage.opsCargoType','opsVoyageExpenditureEntries')
            ->globalSearch($request->all());
            
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
                'attachment'
            );

            if(isset($request->attachment)){
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/voyage_expenditures');
                $voyage_expenditure_info['attachment'] = $attachment;
            }

            $voyageExpenditureEntries = collect($request->opsVoyageExpenditureEntries)->map(function($item) use($request) {
                $item['ops_voyage_id'] = $request->ops_voyage_id;
                $item['port_code'] = $request->port_code;
                return $item;
            })->toArray();

            $voyage_expenditure = OpsVoyageExpenditure::create($voyage_expenditure_info);
            $voyage_expenditure->opsVoyageExpenditureEntries()->createMany($voyageExpenditureEntries);
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
        $voyage_expenditure->load(['opsVoyage','port','opsVessel','opsVoyage.opsCargoType','opsVoyageExpenditureEntries.opsExpenseHead']);
        try
        {
            return response()->success('Data retrieved successfully.', $voyage_expenditure, 200);
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
                'attachment'
            );

            if(isset($request->attachment)){
                $this->fileUpload->deleteFile($voyage_expenditure->attachment);
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/voyage_expenditures');
                $voyage_expenditure_info['attachment'] = $attachment;
            }

            $voyageExpenditureEntries = collect($request->opsVoyageExpenditureEntries)->map(function($item) use($request) {
                $item['ops_voyage_id'] = $request->ops_voyage_id;
                $item['port_code'] = $request->port_code;
                return $item;
            })->toArray();
            
            $voyage_expenditure->update($voyage_expenditure_info);
            $voyage_expenditure->opsVoyageExpenditureEntries()->createUpdateOrDelete($voyageExpenditureEntries);
            DB::commit();
            return response()->success('Data updated successfully.', $voyage_expenditure, 202);
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
            DB::beginTransaction();            
            $voyage_expenditure->opsVoyageExpenditureEntries()->delete();
            $this->fileUpload->deleteFile($voyage_expenditure->attachment);
            $voyage_expenditure->delete();
            DB::commit();
            
            return response()->json([
                'message' => 'Successfully deleted voyage expenditure.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVoyageExpenditureByVoyageWise(Request $request){
        try {
            $voyage_expenditures = OpsVoyageExpenditure::query()
            ->when(isset(request()->ops_voyage_id), function($query){
                $query->where('ops_voyage_id', request()->ops_voyage_id);
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->limit(10)
            ->get();

            return response()->success('Successfully retrieved voyage expendituress name.', $voyage_expenditures, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVoyageExpenditureVoyageWise(Request $request){
        try {
            $voyage_expenditures = OpsVoyageExpenditure::query()
            ->when(isset(request()->ops_voyage_id), function($query){
                $query->where('ops_voyage_id', request()->ops_voyage_id);
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->get();

            return response()->success('Successfully retrieved voyage expendituress name.', $voyage_expenditures, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

}
