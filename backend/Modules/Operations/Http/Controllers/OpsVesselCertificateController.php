<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsVesselCertificate;
use Modules\Operations\Http\Requests\OpsVesselCertificateRequest;

class OpsVesselCertificateController extends Controller
{
   // use HasRoles;
    
    // function __construct()
    // {
    //     $this->middleware('permission:vessel-certificate-create|vessel-certificate-edit|vessel-certificate-show|vessel-certificate-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:vessel-certificate-create', ['only' => ['store']]);
    //     $this->middleware('permission:vessel-certificate-edit', ['only' => ['update']]);
    //     $this->middleware('permission:vessel-certificate-delete', ['only' => ['destroy']]);
    // }
    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $vesselCertificates = OpsVesselCertificate::with('opsVessel','opsMaritimeCertification')
            ->latest()->paginate(15)->groupBy('ops_vessel_id');
            
            return response()->success('Successfully retrieved vessel certificates.', $vesselCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

       /**
     * Store a newly created resource in storage.
     * 
     * @param OpsVesselCertificateRequest $request
     * @return JsonResponse
    */
    public function store(OpsVesselCertificateRequest $request): JsonResponse
    {
        // dd($request);
        try {
            $vesselCertificate = OpsVesselCertificate::create($request->all());
            return response()->success('Vessel certificate added successfully.', $vesselCertificate, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified maritime certification.
     *
     * @param  OpsVesselCertificate  $maritime_certification
     * @return JsonResponse
     */
    public function show(OpsVesselCertificate $vessel_certificate): JsonResponse
    {
        $vessel_certificate->load('opsVessel','opsMaritimeCertification');
        try
        {
            return response()->success('Successfully retrieved vessel certificate.', $vessel_certificate, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

   /**
     * Update the specified resource in storage.
     *
     * @param OpsVesselCertificateRequest $request
     * @param  OpsVesselCertificate  $vessel_certificate
     * @return JsonResponse
     */
    public function update(OpsVesselCertificateRequest $request, OpsVesselCertificate $vessel_certificate): JsonResponse
    {
        // dd($request);
        try {
            $vessel_certificate->update($request->all());
            return response()->success('Vessel certificate updated successfully.', $vessel_certificate, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified vessel from storage.
     *
     * @param  OpsVesselCertificate  $vessel_certificate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsVesselCertificate $vessel_certificate): JsonResponse
    {
        try
        {
            $vessel_certificate->delete();

            return response()->json([
                'message' => 'Successfully deleted vessel certificate.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function search(Request $request) {
        try {
            $vesselCertificate = OpsVesselCertificate::where('reference_number', '=', $request->search)->get();

            return response()->json([
                'value'   => $vesselCertificate,
                'message' => 'Successfully retrieved vessel certificate.',
            ], 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function getVesselCertificateWithoutPaginate(){
        try
        {
            $vesselCertificates = OpsVesselCertificate::all();
            return response()->json([
                'value'   => $vesselCertificates,
                'message' => 'Successfully retrieved vessel certificates.',
            ], 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
