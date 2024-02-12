<?php

namespace Modules\Operations\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVesselCertificate;
use Modules\Operations\Http\Requests\OpsVesselCertificateRequest;

class OpsVesselCertificateController extends Controller
{
    use HasRoles;
        
    function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:ops-vessel-certificate-create|ops-vessel-certificate-edit|ops-vessel-certificate-view|ops-vessel-certificate-delete', ['only' => ['index','show']]);
        $this->middleware('permission:ops-vessel-certificate-create', ['only' => ['store']]);
        $this->middleware('permission:ops-vessel-certificate-edit', ['only' => ['update']]);
        $this->middleware('permission:ops-vessel-certificate-delete', ['only' => ['destroy']]);
    }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $currentDate = Carbon::now();
        try {
            $vesselCertificates= OpsVesselCertificate::with(['opsVessel', 'opsMaritimeCertification'])
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('ops_vessel_certificates')
                    ->groupBy('ops_vessel_id', 'ops_maritime_certification_id');
            })
            ->globalSearch($request->all())
            ->groupBy('ops_vessel_id');
            
            // Calculate days difference using map
            $vesselCertificates->map(function ($certificateGroup, $vesselId) use ($currentDate) {
                return $certificateGroup->map(function ($certificate) use ($currentDate) {                    
                    $expireDate = Carbon::parse($certificate->expire_date);
                    $expire_days = $currentDate->diffInDays($expireDate, false);
                    $certificate->expire_days = $expire_days;

                    return $certificate;
                });
            });

            return response()->success('Data retrieved successfully.', $vesselCertificates, 200);
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
        $exist = OpsVesselCertificate::where(
            [
                'ops_vessel_id' => $request->ops_vessel_id,
                'ops_maritime_certification_id' => $request->ops_maritime_certification_id
            ]
        )->first();

        if(empty($request->isRenew)) {
            if(!empty($exist)){
                $error= [
                    'message'=>'This certificate is already assigned to this vessel.',
                    'errors'=>[
                        'next_port'=>['This certificate is already assigned to this vessel.',
                ]]];
    
                return response()->json($error, 422);
            }
        }
        
        try {
            DB::beginTransaction();
            $vesselCertificate = $request->except(
                '_token',
                'attachment',                
                'type'
            );

            if($request->type == 'Permanent'){
                $vesselCertificate = $request->except(
                    'expire_date',
                );
            }

            if(isset($request->attachment)){
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/vessel_certificates');
                $vesselCertificate['attachment'] = $attachment;
            }
            $vesselCertificate = OpsVesselCertificate::create($vesselCertificate);
            DB::commit();
            return response()->success('Data added successfully.', $vesselCertificate, 201);
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
     * @param  OpsVesselCertificate  $maritime_certification
     * @return JsonResponse
     */
    public function show(OpsVesselCertificate $vessel_certificate): JsonResponse
    {
        $vessel_certificate->load(['opsVessel.opsVesselCertificates'
        =>function ($query) {
            $query->whereIn('ops_vessel_certificates.id', function($query2) {
                $query2->select(DB::raw('MAX(id)'))
                    ->from('ops_vessel_certificates')
                    ->groupBy('ops_maritime_certification_id');
            })->latest();
        }, 'opsMaritimeCertification']);

        $vessel_certificate->opsVessel->opsVesselCertificates->map(function($certificate) {
            $certificate->name = $certificate->opsMaritimeCertification->name;
            $certificate->id = $certificate->opsMaritimeCertification->id;
            return $certificate;
        });
        
        try
        {
            return response()->success('Data retrieved successfully.', $vessel_certificate, 200);
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
            DB::beginTransaction();
            $vesselCertificate = $request->except(
                '_token',
                'attachment',                
                'type'
            );

            if($request->type == 'Permanent'){
                $vesselCertificate = $request->except(
                    'expire_date',
                );
            }

            if(isset($request->attachment)){
                $this->fileUpload->deleteFile($vessel_certificate->attachment);
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/vessel_certificates', $vessel_certificate->attachment);
                $vesselCertificate['attachment'] = $attachment;
            }
            
            $vessel_certificate->update($vesselCertificate);
            DB::commit();
            return response()->success('Data updated Successfully.', $vessel_certificate, 202);
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
     * @param  OpsVesselCertificate  $vessel_certificate
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsVesselCertificate $vessel_certificate): JsonResponse
    {
        try
        {
            DB::beginTransaction();            
            $this->fileUpload->deleteFile($vessel_certificate->attachment);
            $vessel_certificate->delete();
            DB::commit();
            
            return response()->json([
                'message' => 'Data deleted Successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->json($vessel_certificate->preventDeletionIfRelated(), 422);
        }
    }

    public function search(Request $request) {
        try {
            $vesselCertificates = OpsVesselCertificate::where('reference_number', '=', $request->search)->get();

            return response()->success('Data retrieved successfully.', $vesselCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function getVesselCertificateByReferenceNumber(Request $request) {
        try {
            $vesselCertificates = OpsVesselCertificate::query()
                ->when(isset(request()->reference_number), function ($query) {
                    $query->where('reference_number', 'like', '%' . request()->reference_number . '%');                
                })
                // ->whereIn('id', function ($query) {
                //     $query->select(DB::raw('MAX(id)'))
                //         ->from('ops_vessel_certificates')
                //         ->groupBy('ops_vessel_id', 'ops_maritime_certification_id');
                // })
                ->limit(10)
                ->get();

            return response()->success('Data retrieved successfully.', $vesselCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    public function getVesselCertificateReferenceNumber(Request $request) {
        try {
            $vesselCertificates = OpsVesselCertificate::query()
                ->when(isset(request()->reference_number), function ($query) {
                    $query->where('reference_number', 'like', '%' . request()->reference_number . '%');                
                })
                // ->whereIn('id', function ($query) {
                //     $query->select(DB::raw('MAX(id)'))
                //         ->from('ops_vessel_certificates')
                //         ->groupBy('ops_vessel_id', 'ops_maritime_certification_id');
                // })
                ->get();

            return response()->success('Data retrieved successfully.', $vesselCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    public function getIndexRenew(Request $request)
    {
        $days=$request->filter_days;
        $currentDate = Carbon::now();
        try {
            $vesselCertificates= OpsVesselCertificate::with(['opsVessel', 'opsMaritimeCertification'])
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('ops_vessel_certificates')
                    ->groupBy('ops_vessel_id', 'ops_maritime_certification_id');
            })
            ->globalSearch($request->all())
            // ->latest()
            // ->paginate(15)
            ->groupBy('ops_vessel_id');

            $filterCertificates=$vesselCertificates->map(function ($certificateGroup, $vesselId)  use ($currentDate,$days) {
                return $certificateGroup->filter(function ($certificate)  use ($currentDate,$days) {
                    $expireDate = Carbon::parse($certificate->expire_date);
                    $expire_days = $currentDate->diffInDays($expireDate, false);                   
                    $certificate->expire_days = $expire_days;

                    return $certificate->expire_days <= $days && $certificate->opsMaritimeCertification->type != 'Permanent';
                })->values();
            })->filter();

            
            return response()->success('Data retrieved successfully.', $filterCertificates, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


}
