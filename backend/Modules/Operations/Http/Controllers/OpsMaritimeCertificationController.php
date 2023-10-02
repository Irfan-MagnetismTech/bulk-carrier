<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsMaritimeCertification;
use Modules\Operations\Http\Requests\OpsMaritimeCertificationRequest;

class OpsMaritimeCertificationController extends Controller
{
    // use HasRoles;
    
    // function __construct()
    // {
    //     $this->middleware('permission:maritime-certification-create|maritime-certification-edit|maritime-certification-show|maritime-certification-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:maritime-certification-create', ['only' => ['store']]);
    //     $this->middleware('permission:maritime-certification-edit', ['only' => ['update']]);
    //     $this->middleware('permission:maritime-certification-delete', ['only' => ['destroy']]);
    // }
    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $maritimeCertifications = OpsMaritimeCertification::latest()->paginate(15);
            
            return response()->success('Successfully retrieved Maritime Certifications.', $maritimeCertification, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param OpsMaritimeCertificationRequest $request
     * @return JsonResponse
    */
    public function store(OpsMaritimeCertificationRequest $request): JsonResponse
    {
        // dd($request);
        try {
            $maritimeCertification = OpsMaritimeCertification::create($request->all());
            return response()->success('Maritime Certification added Successfully.', $maritimeCertification, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified maritime certification.
     *
     * @param  OpsMaritimeCertification  $maritime_certification
     * @return JsonResponse
     */
    public function show(OpsMaritimeCertification $maritime_certification): JsonResponse
    {
        // dd($maritime);
        try
        {
            return response()->success('Successfully retrieved Maritime Certification.', $maritime_certification, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param OpsMaritimeCertificationRequest $request
     * @param  OpsMaritimeCertification  $maritime_certification
     * @return JsonResponse
     */
    public function update(OpsMaritimeCertificationRequest $request, OpsMaritimeCertification $maritime_certification): JsonResponse
    {
        // dd($request);
        try {
            $maritime_certification->update($request->all());
            return response()->success('Maritime certification updated successfully.', $maritime_certification, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified vessel from storage.
     *
     * @param  OpsMaritimeCertification  $maritime_certification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsMaritimeCertification $maritime_certification): JsonResponse
    {
        try
        {
            $maritime_certification->delete();

            return response()->json([
                'message' => 'Successfully deleted maritime certification.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
