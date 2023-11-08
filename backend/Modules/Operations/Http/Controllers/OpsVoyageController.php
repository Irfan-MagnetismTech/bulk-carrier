<?php

namespace Modules\Operations\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Http\Requests\OpsVoyageRequest;
use Modules\Operations\Http\Exports\VoyageReportExport;

class OpsVoyageController extends Controller
{
//    use HasRoles;
   
//    function __construct(private FileUploadService $fileUpload)
//    {
//        $this->middleware('permission:voyage-create|voyage-edit|voyage-show|voyage-delete', ['only' => ['index','show']]);
//        $this->middleware('permission:voyage-create', ['only' => ['store']]);
//        $this->middleware('permission:voyage-edit', ['only' => ['update']]);
//        $this->middleware('permission:voyage-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    **/
    public function index()
    {
        // dd('voyage');
        try {
            $voyages = OpsVoyage::with('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved voyage.', $voyages, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsVoyageRequest $request
      * @return JsonResponse
     */
     public function store(OpsVoyageRequest $request): JsonResponse
     {
        // dd($request);
        try {
            DB::beginTransaction();
            $voyageInfo = $request->except(
                '_token',
                'opsVoyageSectors',
                'opsVoyagePortSchedules',
                'opsBunkers',
            );

            $voyage = OpsVoyage::create($voyageInfo);
            $voyage->opsVoyageSectors()->createMany($request->opsVoyageSectors);
            $voyage->opsVoyagePortSchedules()->createMany($request->opsVoyagePortSchedules);
            $voyage->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Voyage added successfully.', $voyage, 201);
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
      * @param  OpsVoyage  $voyage
      * @return JsonResponse
      */
     public function show(OpsVoyage $voyage): JsonResponse
     {
        $voyage->load('opsCustomer','opsVessel','opsCargoType','opsVoyageSectors','opsVoyagePortSchedules','opsBunkers');

        try
        {
            return response()->success('Successfully retrieved voyage.', $voyage, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
 
     }
 
 
       /**
      * Update the specified resource in storage.
      *
      * @param OpsVoyageRequest $request
      * @param  OpsVoyageRequest  $voyage
      * @return JsonResponse
      */
     public function update(OpsVoyageRequest $request, OpsVoyage $voyage): JsonResponse
     {
        try {            
            DB::beginTransaction();
            $voyageInfo = $request->except(
                '_token',
                'opsVoyageSectors',
                'opsVoyagePortSchedules',
                'opsBunkers',
            );

            $voyage->update($voyageInfo);  

            $voyage->opsVoyageSectors()->delete();
            $voyage->opsVoyageSectors()->createMany($request->opsVoyageSectors);

            $voyage->opsVoyagePortSchedules()->delete();
            $voyage->opsVoyagePortSchedules()->createMany($request->opsVoyagePortSchedules);

            $voyage->opsBunkers()->delete();
            $voyage->opsBunkers()->createMany($request->opsBunkers);

            DB::commit();
            return response()->success('Voyage updated successfully.', $voyage, 202);
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
      * @param  OpsVoyage  $voyage
      * @return \Illuminate\Http\JsonResponse
      */
    public function destroy(OpsVoyage $voyage): JsonResponse
    {
        try
        {
            $voyage->opsVoyageSectors()->delete();
            $voyage->opsVoyagePortSchedules()->delete();
            $voyage->opsBunkers()->delete();
            $voyage->delete();

            return response()->json([
                'message' => 'Successfully deleted voyage.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchVoyages(Request $request){
        try {
            $voyages = OpsVoyage::query()
            ->where(function ($query) use($request) {
                $query->where('voyage_no', 'like', '%' . $request->voyage_no . '%');                
            })
            ->when(request()->vessel_id != null, function($q){
                $q->where('ops_vessel_id', request()->vessel_id);
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->limit(10)
            ->get();

            return response()->success('Successfully retrieved voyages.', $voyages, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
    
    public function exportVoyageReport(Request $request)
    {
        $voyage_reports['data'] = OpsVoyage::with(
            'opsCustomer',
            'opsVessel.opsCargoTariff',
            'opsCargoType',
            'opsVoyageSectors',
            'opsVoyagePortSchedules',
            'opsBunkers',
            'opsVoyageExpenditure.opsVoyageExpenditureEntries'
        )
        ->where('ops_vessel_id', $request->vessel_id)       
        ->when(request()->from_date && request()->till_date, function ($query) {
            $query->whereDate('created_at', '>=', Carbon::parse(request()->from_date))
                  ->whereDate('created_at', '<=', Carbon::parse(request()->till_date));
        })
        ->get();
        
        $vessel=null;
        if($request->vessel_id){
            $vessel= OpsVessel::find($request->vessel_id);
        }
        
        $voyage_reports['vessel_name']=$vessel?->name;
        // dd($voyage_reports);

        return Excel::download(new VoyageReportExport($voyage_reports), 'LighterNoonReport.xlsx');
        
    }

}
