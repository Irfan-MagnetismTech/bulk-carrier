<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsCargoTariff;
use Modules\Operations\Http\Requests\OpsCargoTariffRequest;
use Illuminate\Support\Facades\DB;

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
   public function index()
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
    public function show(OpsVesselParticular $cargo_tariff): JsonResponse
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
     * @param OpsVesselParticularRequest $request
     * @param  OpsVesselParticular  $vessel_particular
     * @return JsonResponse
     */
    public function update(OpsVesselParticularRequest $request, OpsVesselParticular $vessel_particular): JsonResponse
    {
        try {
            DB::beginTransaction();
            $cargoTariffInfo = $request->except(
                '_token',
                'opsCargoTariffLines',
            );
           
            $cargoTariff->update($cargoTariffInfo);            
            $cargoTariff->opsCargoTariffLines()->delete();
            $cargoTariff->opsCargoTariffLines()->createMany($request->opsCargoTariffLines);
            DB::commit();
            return response()->success('Cargo tariff updated successfully.', $vessel_particular, 200);
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
     * @param  OpsVesselParticular  $vessel_particular
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsVesselCertificate $vessel_particular): JsonResponse
    {
        try
        {
            $vessel_particular->opsCargoTariffLines()->delete();
            $vessel_particular->delete();

            return response()->json([
                'message' => 'Successfully deleted vessel certificate.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
