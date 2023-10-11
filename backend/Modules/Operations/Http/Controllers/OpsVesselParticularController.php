<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsVesselParticular;
use Modules\Operations\Http\Requests\OpsVesselParticularRequest;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Auth;

class OpsVesselParticularController extends Controller
{
   // use HasRoles;
   function __construct(private FileUploadService $fileUpload,)
   {
    //     $this->middleware('permission:vessel-particular-create|vessel-particular-edit|vessel-particular-show|vessel-particular-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:vessel-particular-create', ['only' => ['store']]);
    //     $this->middleware('permission:vessel-particular-edit', ['only' => ['update']]);
    //     $this->middleware('permission:vessel-particular-delete', ['only' => ['destroy']]);
    }
    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $vesselParticular = OpsVesselParticular::with('opsVessel')->latest()->paginate(15);
            return response()->success('Successfully retrieved vessel particular.', $vesselParticular, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


       /**
     * Store a newly created resource in storage.
     * 
     * @param OpsVesselParticularRequest $request
     * @return JsonResponse
    */
    public function store(OpsVesselParticularRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $vesselParticular = $request->except(
                '_token',
                'attachment',
            );
            $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/vessel_particulars');
            $vesselParticular['attachment'] = $attachment;
            $vesselParticular = OpsVesselParticular::create($vesselParticular);
            DB::commit();
            return response()->success('Vessel particular added successfully.', $vesselParticular, 201);
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
     * @param  OpsVesselParticular  $maritime_certification
     * @return JsonResponse
     */
    public function show(OpsVesselParticular $vessel_particular): JsonResponse
    {
        $vessel_particular->load('ops_vessel');
        try
        {
            return response()->success('Successfully retrieved vessel particular.', $vessel_particular, 200);
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
            $vesselParticular = $request->except(
                '_token',
                'attachment',
            );

            if(isset($request->attachment)){
                $this->fileUpload->deleteFile($vessel_particular->attachment);
                $attachment = $this->fileUpload->handleFile($request->attachment, 'ops/vessel_particulars', $vessel_particular->attachment);
                $vesselParticular['attachment'] = $attachment;
            }
            
            $vessel_particular->update($vesselParticular);
            DB::commit();
            return response()->success('Vessel particular updated successfully.', $vessel_particular, 200);
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
    public function destroy(OpsVesselParticular $vessel_particular): JsonResponse
    {
        try
        {
            $this->fileUpload->deleteFile($vessel_particular->attachment);
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

    public function getVesselParticularName(){
        try {
            $vessel_particulars = OpsVesselParticular::with('ops_vessel')->latest()->get();
            return response()->success('Successfully retrieved vessel particulars name.', collect($vessel_particulars->pluck('name'))->unique()->values()->all(), 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


}
