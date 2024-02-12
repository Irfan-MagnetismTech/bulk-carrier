<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\Operations\Entities\OpsVesselBunker;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\Operations\Http\Requests\OpsVesselBunkerRequest;

class OpsVesselBunkerController extends Controller
{
    use HasRoles;

    function __construct()
    {
        $this->middleware('permission:ops-vessel-bunker-create|ops-vessel-bunker-edit|ops-vessel-bunker-view|ops-vessel-bunker-delete', ['only' => ['index','show']]);
        $this->middleware('permission:ops-vessel-bunker-create', ['only' => ['store']]);
        $this->middleware('permission:ops-vessel-bunker-edit', ['only' => ['update']]);
        $this->middleware('permission:ops-vessel-bunker-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the vessel_bunker.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try
        {
            $vessel_bunkers = OpsVesselBunker::with([
                'opsVessel',
                'opsVoyage',
                'opsBunkers.scmMaterial'
            ])
            ->globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $vessel_bunkers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created vessel in storage.
     *
     * @param  OpsVesselBunkerRequest  $request
     * @return JsonResponse
     */
    public function store(OpsVesselBunkerRequest $request): JsonResponse
    {
        try
        {
            DB::beginTransaction();
            $vessel_bunkerInfo = $request->except(
                '_token',
                'opsBunkers.scmMaterial',
            );

            if (count($request->opsBunkers)<1) {
                $error= [
                    'message'=>'Must be add at least one item.',
                    'errors'=>[
                        'bunker'=>['Must be add at least one item.']
                        ]
                    ];
                return response()->json($error, 422);
            }
            
            // dd($request->all());
            $vessel_bunker = OpsVesselBunker::create($vessel_bunkerInfo);
            
            $warehouse= ScmWarehouse::where('ops_vessel_id', $request->ops_vessel_id)->first();
            $vessel_bunker['scm_warehouse_id']= $warehouse->id;
            $bunkers= collect($request->opsBunkers)->map(function($bunker) use ($warehouse){
                return $bunker;
            })->values()->all();
            


            $vessel_bunker->opsBunkers()->createMany($bunkers);
            
            if($request->type=="Stock In") {
                (new StockLedgerData)->insert($vessel_bunker, $bunkers);
            }else if($request->type=="Stock Out"){
                $dataForStock = [];
                foreach ($bunkers as $key => $value) {
                    $dataForStock[] =(new StockLedgerData)->out($value['scm_material_id'], $warehouse->id, $value['quantity']);
                }
                $dataForStockLedger = array_merge(...$dataForStock);
                $vessel_bunker->stockable()->createMany($dataForStockLedger);
            }

            DB::commit();

            return response()->success('Data added successfully.', $vessel_bunker, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified vessel_bunker.
     *
     * @param  OpsVesselBunker  $vessel_bunker
     * @return JsonResponse
     */
    public function show(OpsVesselBunker $vessel_bunker): JsonResponse
    {
        $vessel_bunker->load([
            'opsVessel',
            'opsVoyage',
            'opsBunkers.scmMaterial',
            'opsBunkers.scmVendor',
        ]);

        $vessel_bunker->opsBunkers->map(function($bunker) {
            $bunker->id = $bunker->scmMaterial->id;
            $bunker->name = $bunker->scmMaterial->name;
            $bunker->is_readonly = true;
            return $bunker;
        });

        try
        {
            return response()->success('Data retrieved successfully.', $vessel_bunker, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified vessel in storage.
     *
     * @param  OpsVesselBunkerRequest  $request
     * @param  OpsVesselBunker  $vessel_bunker
     * @return JsonResponse
     */
    public function update(OpsVesselBunkerRequest $request, OpsVesselBunker $vessel_bunker): JsonResponse
    {
        // dd($request);
        // Update is disabled. Enabling it requires checking and update chained stock data.
        try
        {
            DB::beginTransaction();
            $vessel_bunkerInfo = $request->except(
                '_token',
                'opsBunkers',
            );
            $vessel_bunker->update($vessel_bunkerInfo);
            $vessel_bunker->opsBunkers()->delete();
            $vessel_bunker->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Data updated successfully.', $vessel_bunker, 202);
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
     * @param  OpsVesselBunker  $vessel_bunker
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsVesselBunker $vessel_bunker): JsonResponse
    {
        try
        {
            DB::beginTransaction();
            $vessel_bunker->opsBunkers()->delete();
            $vessel_bunker->delete();
            DB::commit();
            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($vessel_bunker->preventDeletionIfRelated(), 422);
        }
    }
}
