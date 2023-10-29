<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsCargoTariff;
use Modules\Operations\Http\Requests\OpsCargoTariffRequest;
use Modules\Operations\Http\Requests\OpsCargoTariffLineRequest;

class OpsCargoTariffController extends Controller
{
   // use HasRoles;
   
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:cargo-tariff-create|cargo-tariff-edit|cargo-tariff-show|cargo-tariff-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:cargo-tariff-create', ['only' => ['store']]);
   //     $this->middleware('permission:cargo-tariff-edit', ['only' => ['update']]);
   //     $this->middleware('permission:cargo-tariff-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
   public function index(Request $request): JsonResponse
   {
       try {
           $cargoTariffs = OpsCargoTariff::with('opsVessel','opsCargoType','opsCargoTariffLines')->latest()->paginate(15);
           
           return response()->success('Successfully retrieved cargo tariffs.', $cargoTariffs, 200);
       }
       catch (QueryException $e)
       {
           return response()->error($e->getMessage(), 500);
       }
   }


       /**
     * Store a newly created resource in storage.
     * 
     * @param OpsCargoTariffRequest $request
     * @return JsonResponse
    */
    public function store(OpsCargoTariffRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $cargoTariffInfo = $request->except(
                '_token',
                'opsCargoTariffLines',
            );

            $cargoTariff = OpsCargoTariff::create($cargoTariffInfo);
            $cargoTariff->opsCargoTariffLines()->createMany($request->opsCargoTariffLines);
            DB::commit();
            return response()->success('Cargo tariff added successfully.', $cargoTariff, 201);
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
     * @param  OpsCargoTariff  $cargo_tariff
     * @return JsonResponse
     */
    public function show(OpsCargoTariff $cargo_tariff): JsonResponse
    {
        $cargo_tariff->load('opsVessel','opsCargoType','opsCargoTariffLines');
        try
        {
            return response()->success('Successfully retrieved cargo tariff.', $cargo_tariff, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


      /**
     * Update the specified resource in storage.
     *
     * @param OpsCargoTariffRequest $request
     * @param  OpsCargoTariff  $cargo_tariff
     * @return JsonResponse
     */
    public function update(OpsCargoTariffRequest $request, OpsCargoTariff $cargo_tariff): JsonResponse
    {
        try {
            DB::beginTransaction();
            $cargoTariffInfo = $request->except(
                '_token',
                'opsCargoTariffLines',
            );
                       
            $cargo_tariff->update($cargoTariffInfo);            
            $cargo_tariff->opsCargoTariffLines()->createUpdateOrDelete($request->opsCargoTariffLines);
            DB::commit();
            return response()->success('Cargo tariff updated successfully.', $cargo_tariff, 200);
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
     * @param  OpsCargoTariff  $cargo_tariff
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsCargoTariff $cargo_tariff): JsonResponse
    {
        try
        {
            $cargo_tariff->opsCargoTariffLines()->delete();
            $cargo_tariff->delete();

            return response()->json([
                'message' => 'Successfully deleted vessel certificate.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    

    public function getCargoTariffByName(Request $request){
        try {
            $cargoTariffs = OpsCargoTariff::query()
            ->where(function ($query) use($request) {
                $query->where('tariff_name', 'like', '%' . $request->tariff_name . '%');                
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->limit(10)
            ->get();

            return response()->success('Successfully retrieved cargo tariffs name.', $cargoTariffs, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
