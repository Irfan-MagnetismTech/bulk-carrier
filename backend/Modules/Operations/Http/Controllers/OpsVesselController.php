<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVessel;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Http\Requests\OpsVesselRequest;

class OpsVesselController extends Controller
{
    // use HasRoles;

    // function __construct()
    // {
    //     $this->middleware('permission:vessel-create|vessel-edit|vessel-show|vessel-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:vessel-create', ['only' => ['store']]);
    //     $this->middleware('permission:vessel-edit', ['only' => ['update']]);
    //     $this->middleware('permission:vessel-delete', ['only' => ['destroy']]);
    // }

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
                'opsBunkers'
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
            'portOfRegistry'
        ]);

        $vessel->opsVesselCertificates->map(function($certificate) {
            $certificate->type = $certificate->opsMaritimeCertification->type;
            $certificate->validity  =$certificate->opsMaritimeCertification->validity;
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->opsMaritimeCertification->id;
            return $certificate;
        });

        $vessel->opsBunkers->map(function($bunker) {
            $bunker->id = $bunker->scmMaterial->id;
            $bunker->name = $bunker->scmMaterial->name;
            $bunker->is_readonly = true;
            return $bunker;
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
            $vessel->opsVesselCertificates()->delete();
            $vessel->opsBunkers()->delete();
            $vessel->delete();
            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
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
            'opsBunkers'
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
            'opsBunkers'
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


}
