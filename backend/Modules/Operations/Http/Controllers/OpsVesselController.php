<?php

namespace Modules\Operations\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVessel;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Entities\AccCostCenter;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Services\StockLedgerData;
use Modules\Operations\Http\Requests\OpsVesselRequest;

class OpsVesselController extends Controller
{
    use HasRoles;

    function __construct()
    {
        $this->middleware('permission:ops-vessel-create|ops-vessel-edit|ops-vessel-view|ops-vessel-delete', ['only' => ['index','show']]);
        $this->middleware('permission:ops-vessel-create', ['only' => ['store']]);
        $this->middleware('permission:ops-vessel-edit', ['only' => ['update']]);
        $this->middleware('permission:ops-vessel-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the vessel.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try
        {
            $vessels = OpsVessel::with([
                'opsVesselCertificates' => function ($query){
                    $query->whereIn('ops_vessel_certificates.id', function($query2) {
                        $query2->select(DB::raw('MAX(id)'))
                            ->from('ops_vessel_certificates')
                            ->groupBy('ops_maritime_certification_id');
                    })->latest();
                },
                // 'opsBunkers',             
                'scmWareHouse.scmWarehouseContactPersons',
                'scmWareHouse.accCostCenter'
            ])
            ->globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $vessels, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created vessel in storage.
     *
     * @param  OpsVesselRequest  $request
     * @return JsonResponse
     */
    public function store(OpsVesselRequest $request): JsonResponse
    {
        try
        {
            DB::beginTransaction();
            $vesselInfo = $request->except(
                '_token',
                'opsVesselCertificates',
                'opsBunkers.scmMaterial',
            );
            $vessel = OpsVessel::create($vesselInfo);
            $vessel->opsVesselCertificates()->createMany($request->opsVesselCertificates);
            $vessel->opsBunkers()->createMany($request->opsBunkers);

            // create cost center
            $costCenter= [
                'ops_vessel_id'=>$vessel->id,
                'name'=>$request->name,
                'short_name'=>$request->short_code,
                'type'=>$request->vessel_type,
                'business_unit'=>$request->business_unit,
            ];
            
            $cost_center=AccCostCenter::create($costCenter);

            // create ware house
            $wareHouse= [
                'ops_vessel_id'=>$vessel->id,
                'cost_center_id'=>$cost_center->id,
                'name'=>$request->name,
                'short_code'=>$request->short_code,
                'address'=>$request->name,
                'business_unit'=>$request->business_unit,
            ];
            
            $wareHouseContact= [
                'name'=>$request->manager,
                'assign_date'=>today(),
            ];

            $ware_house=ScmWarehouse::create($wareHouse);
            $vessel['scm_warehouse_id']= $ware_house->id;

            $bunkers=$vessel->opsBunkers->map(function($bunker) {
                $bunker['quantity'] = $bunker->opening_balance;
                return $bunker;
            });
            
            $ware_house->scmWarehouseContactPersons()->create($wareHouseContact);

            DB::commit();

            return response()->success('Data added successfully.', $vessel, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified vessel.
     *
     * @param  OpsVessel  $vessel
     * @return JsonResponse
     */
    public function show(OpsVessel $vessel): JsonResponse
    {
        $vessel->load([
            'opsVesselCertificates' => function ($query){
                $query->whereIn('ops_vessel_certificates.id', function($query2) {
                    $query2->select(DB::raw('MAX(id)'))
                        ->from('ops_vessel_certificates')
                        ->groupBy('ops_maritime_certification_id');
                })->latest();
            },
            'opsBunkers.scmMaterial',
            'portOfRegistry',
            'scmWareHouse.scmWarehouseContactPersons',
            'scmWareHouse.accCostCenter'
        ]);

        $vessel->opsVesselCertificates->map(function($certificate) {
            $certificate->type = $certificate->opsMaritimeCertification->type;
            $certificate->validity  =$certificate->opsMaritimeCertification->validity;
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->opsMaritimeCertification->id;
            $certificate->certificate = [
                'type' => $certificate->opsMaritimeCertification->type,
                'validity'  => $certificate->opsMaritimeCertification->validity,
                'name' => $certificate->opsMaritimeCertification->name,
                'id' => $certificate->opsMaritimeCertification->id,
            ];
            return $certificate;
        });
        

        $vessel->opsBunkers->map(function($bunker) {
            $bunker->id = $bunker->scmMaterial->id;
            $bunker->name = $bunker->scmMaterial->name;
            $bunker->is_readonly = true;
            $bunker->bunker = [
                'id' => $bunker->scmMaterial->id,
                'name' => $bunker->scmMaterial->name,
                'unit' => $bunker->scmMaterial->unit,
            ];
            return $bunker;
        });

        $vessel->total_voyages = $vessel->opsVoyages->count();
        $vessel->currentCharterer =  $vessel->currentCharterer;

        try
        {
            return response()->success('Data retrieved successfully.', $vessel, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified vessel in storage.
     *
     * @param  OpsVesselRequest  $request
     * @param  OpsVessel  $vessel
     * @return JsonResponse
     */
    public function update(OpsVesselRequest $request, OpsVessel $vessel): JsonResponse
    {
        // dd($request);
        try
        {
            DB::beginTransaction();
            $vesselInfo = $request->except(
                '_token',
                'opsVesselCertificates',
                // 'opsBunkers',
            );
            // dd($request);
            $vessel->update($vesselInfo);
            $vessel->opsVesselCertificates()->delete();
            $vessel->opsVesselCertificates()->createMany($request->opsVesselCertificates);
            $vessel->opsBunkers()->delete();
            $vessel->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Data updated successfully.', $vessel, 202);
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
     * @param  OpsVessel  $vessel
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsVessel $vessel): JsonResponse
    {
        try
        {
            DB::beginTransaction();
            $vessel->opsVesselCertificates()->delete();
            // $vessel->opsBunkers()->delete();
            $vessel->delete();            
            DB::commit();
            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($vessel->preventDeletionIfRelated(), 422);
        }
    }

    public function search(Request $request) {
        try {
            $vessel = OpsVessel::where('name', 'like', '%' . $request->search . '%')->get();
            return response()->success('Data retrieved successfully.', $vessel, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function getVesselByNameorCode(Request $request){
        try {
            $vessels = OpsVessel::query()
            ->when(isset(request()->name_or_code), function ($query) {
                $query->where('name', 'like', '%' . request()->name_or_code . '%');
                $query->orWhere('code', 'like', '%' . request()->name_or_code . '%');
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->limit(10)
            ->get();

            return response()->success('Data retrieved successfully.', $vessels, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVesselNameorCode(Request $request){
        try {
            $vessels = OpsVessel::query()
            ->when(isset(request()->name_or_code), function ($query) {
                $query->where('name', 'like', '%' . request()->name_or_code . '%');
                $query->orWhere('code', 'like', '%' . request()->name_or_code . '%');
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })
            ->get();

            return response()->success('Data retrieved successfully.', $vessels, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    // filter only vessel id wise
    public function getVesselLatest(Request $request): JsonResponse
    {
        $vessel= OpsVessel::with([
            'opsVesselCertificates' => function ($query) use ($request) {
                $query->whereIn('ops_vessel_certificates.id', function($query2) {
                    $query2->select(DB::raw('MAX(id)'))
                        ->from('ops_vessel_certificates')
                        ->groupBy('ops_maritime_certification_id');
                })->latest();
            },
            // 'opsBunkers'
        ])->find($request->vessel_id);

        $vessel->opsVesselCertificates->map(function($certificate) {
            $certificate->type = $certificate->opsMaritimeCertification->type;
            $certificate->validity  =$certificate->opsMaritimeCertification->validity;
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->id;
            return $certificate;
        });
        try
        {
            return response()->success('Data retrieved successfully.', $vessel, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }
    public function getVesselCertificateHistory(Request $request): JsonResponse
    {
        // dd($request);
        $vessel= OpsVessel::with([
            'opsVesselCertificates' => function ($query) use ($request) {
                $query->where('ops_maritime_certification_id', $request->certificate_id);
            },
            // 'opsBunkers'
        ])->find($request->vessel_id);
        $currentDate = Carbon::now();

        $vessel->opsVesselCertificates->map(function($certificate) use($currentDate) {
            $certificate->type = $certificate->opsMaritimeCertification->type;
            $certificate->validity  =$certificate->opsMaritimeCertification->validity;
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->id;

            $expireDate = Carbon::parse($certificate->expire_date);
                    $expire_days = $currentDate->diffInDays($expireDate, false);
                    $certificate->expire_days = $expire_days;
            return $certificate;
        });
        try
        {
            return response()->success('Data retrieved successfully.', $vessel, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


}
