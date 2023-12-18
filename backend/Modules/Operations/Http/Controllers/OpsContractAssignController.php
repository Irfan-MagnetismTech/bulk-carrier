<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVoyage;
use Modules\Operations\Entities\OpsContractAssign;
use Illuminate\Contracts\Supcontract_assign\Renderable;
use Modules\Operations\Http\Requests\OpsContractAssignRequest;

class OpsContractAssignController extends Controller
{
    // use HasRoles;   
    
    // function __construct()
    // {
    //     $this->middleware('permission:contract-assign-create|contract-assign-edit|contract-assign-show|contract-assign-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:contract-assign-create', ['only' => ['store']]);
    //     $this->middleware('permission:contract-assign-edit', ['only' => ['update']]);
    //     $this->middleware('permission:contract-assign-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            // dd($request->all());
            $contract_assigns = OpsContractAssign::with('opsVessel','opsVoyage','opsCargoTariff', 'opsCustomer', 'opsChartererProfile','opsChartererContract')
            ->globalSearch($request->all());
            return response()->success('Data retrieved successfully.', $contract_assigns, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


    
    /**
     * Store a newly created resource in storage.
     * @param OpsContractAssignRequest $request
     * @return JsonResponse
     */
    public function store(OpsContractAssignRequest $request): JsonResponse
    // public function store(Request $request): JsonResponse
    {
        // dd($request);
        // return response()->json($request->opsVoyage['opsContractTariffs']);
        try {
            DB::beginTransaction();
            $tariffs = collect($request->opsVoyage['opsContractTariffs'])->map(function($item) use($request) {
                $item['ops_voyage_sector_id'] = $item['id'];
                $item['ops_vessel_id'] = $request->ops_vessel_id;
                return $item;
            })->toArray();
            

            $info = $request->all();
            $info['pol_pod'] = $request->loading_point.'-'.$request->unloading_point;

            $contract_assigns = OpsContractAssign::create($info);
            $contract_assigns->opsContractTariffs()->createMany($tariffs);
            DB::commit();   
            return response()->success('Data added successfully.', $contract_assigns, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified contract_assign.
     *
     * @param  OpsContractAssign  $contract_assign
     * @return JsonResponse
     */
    public function show(OpsContractAssign $contract_assign): JsonResponse
    {
        $contract_assign->load('opsVessel','opsVoyage.opsContractTariffs.opsCargoTariff.opsCargoTariffLines','opsVoyage.opsVoyageSectors.cargoTariffs.opsCargoTariffLines','opsCargoTariff', 'opsCustomer', 'opsChartererProfile','opsChartererContract');
       
        $contract_assign->opsVoyage->opsVoyageSectors->map(function ($sector) {

            if (isset($sector->cargoTariffs)) {
                // dd($sector->cargoTariffs);
                $sectorInfo = $sector->cargoTariffs()->get()->map(function ($tariff) {
                    $tariff['jan'] = $tariff->opsCargoTariffLines->sum('jan');
                    $tariff['feb'] = $tariff->opsCargoTariffLines->sum('feb');
                    $tariff['mar'] = $tariff->opsCargoTariffLines->sum('mar');
                    $tariff['apr'] = $tariff->opsCargoTariffLines->sum('apr');
                    $tariff['may'] = $tariff->opsCargoTariffLines->sum('may');
                    $tariff['jun'] = $tariff->opsCargoTariffLines->sum('jun');
                    $tariff['jul'] = $tariff->opsCargoTariffLines->sum('jul');
                    $tariff['aug'] = $tariff->opsCargoTariffLines->sum('aug');
                    $tariff['sep'] = $tariff->opsCargoTariffLines->sum('sep');
                    $tariff['oct'] = $tariff->opsCargoTariffLines->sum('oct');
                    $tariff['nov'] = $tariff->opsCargoTariffLines->sum('nov');
                    $tariff['dec'] = $tariff->opsCargoTariffLines->sum('dec');
                    
                    
                    return $tariff;
                });
                data_forget($sector, 'cargoTariffs');
                
                $sector['cargoTariffs'] = $sectorInfo;
                
            }
            return $sector;
        });

        $contract_assign->opsVoyage->opsContractTariffs->map(function($item) use($contract_assign) {

            $item['cargoTariffs'] = $contract_assign->opsVoyage->opsVoyageSectors->where('pol_pod', $item['pol_pod'])?->first()?->cargoTariffs;
            $item['opsCargoTariffs'] = $contract_assign->opsVoyage->opsVoyageSectors->where('pol_pod', $item['pol_pod'])?->first()?->cargoTariffs;
            return $item;
        });
   
        
        try {     
            return response()->success('Data retrieved successfully.', $contract_assign, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsContractAssignRequest  $request
     * @param  OpsContractAssign  $contract_assign
     * @return JsonResponse
     */
    public function update(OpsContractAssignRequest $request, OpsContractAssign $contract_assign): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $tariffs = collect($request->opsVoyage['opsContractTariffs'])->map(function($item) {
                $item['ops_voyage_sector_id'] = $item['id'];
                return $item;
            })->toArray();

            $info = $request->all();
            $info['pol_pod'] = $request->loading_point.'-'.$request->unloading_point;

            $contract_assign->opsContractTariffs()->delete();
            $contract_assign->update($info);
            $contract_assign->opsContractTariffs()->createMany($tariffs);
            DB::commit();  
            return response()->success('Data updated Successfully.', $contract_assign, 202);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsContractAssign  $contract_assign
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsContractAssign $contract_assign): JsonResponse
    {
        try {
            $contract_assign->delete($contract_assign);
            $contract_assign->opsContractTariffs()->delete();

            return response()->json([
                'value' => '',
                'message' => 'Data deleted Successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);        
        }
    }

}
