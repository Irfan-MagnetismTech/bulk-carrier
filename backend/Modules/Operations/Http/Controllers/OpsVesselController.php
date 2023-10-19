<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Http\Requests\OpsVesselRequest;
use Illuminate\Support\Facades\DB;

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
    public function index(): JsonResponse
    {
        try
        {
            $vessels = OpsVessel::with('opsVesselCertificates','opsBunkers')
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->latest()->paginate(10);                     
            return response()->success('Successfully retrieved vessels.', $vessels, 200);
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
                'opsBunkers',
            );
            $vessel = OpsVessel::create($vesselInfo);
            $vessel->opsVesselCertificates()->createMany($request->opsVesselCertificates);
            $vessel->opsBunkers()->createMany($request->opsVesselCertificates);
            DB::commit();
                 
            return response()->success('Successfully created vessel.', $vessel, 201);
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
        $vessel->load('opsVesselCertificates','opsBunkers');
        $vessel->opsVesselCertificates->map(function($certificate) {
            $certificate->type = $certificate->opsMaritimeCertification->type;
            $certificate->validity  =$certificate->opsMaritimeCertification->validity;
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->opsMaritimeCertification->id;
            return $certificate;
        });
        try
        {            
            return response()->success('Successfully retrieved vessel.', $vessel, 200);
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
            return response()->success('Successfully updated vessel.', $vessel, 202);
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
                'message' => 'Successfully deleted vessel.',
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
            return response()->success('Successfully retrieved vessels.', $vessel, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    
    public function getVesselByName(Request $request){
        try {
            $vessels = OpsVessel::query()
            ->where(function ($query) use($request) {
                $query->where('name', 'like', '%' . $request->name_or_code . '%');
                $query->orWhere('short_code', 'like', '%' . $request->name_or_code . '%');
            })
            ->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })
            ->limit(10)
            ->pluck(DB::raw("CONCAT(name, ' - ', short_code) AS vessel_name"),'id');
            return response()->success('Successfully retrieved vessels name.', $vessels, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


}
