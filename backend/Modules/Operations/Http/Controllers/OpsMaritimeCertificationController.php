<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
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
    public function index(Request $request): JsonResponse
    {
        // dd($request);
        try {
            $maritimeCertifications = OpsMaritimeCertification::globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $maritimeCertifications, 200);
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
            DB::beginTransaction();
            $maritimeCertification = OpsMaritimeCertification::create($request->all());
            DB::commit();
            return response()->success('Data added successfully.', $maritimeCertification, 201);
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
     * @param  OpsMaritimeCertification  $maritime_certification
     * @return JsonResponse
     */
    public function show(OpsMaritimeCertification $maritime_certification): JsonResponse
    {
        // dd($maritime);
        try
        {
            return response()->success('Data retrieved successfully.', $maritime_certification, 200);
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
            DB::beginTransaction();
            $maritime_certification->update($request->all());
            DB::commit();
            return response()->success('Data updated successfully.', $maritime_certification, 202);
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
     * @param  OpsMaritimeCertification  $maritime_certification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsMaritimeCertification $maritime_certification): JsonResponse
    {
        try
        {
            $maritime_certification->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    
    public function getMaritimeCertificationByName(Request $request){
        try {
            $maritime_certifications = OpsMaritimeCertification::query()
            ->when(request()->has('name'), function ($query) {
                $query->where('name', 'like', '%' . request()->name . '%');                
            })
            ->limit(10)
            ->get();
            return response()->success('Data retrieved successfully.', $maritime_certifications, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getMaritimeCertificationName(Request $request){
        try {
            $maritime_certifications = OpsMaritimeCertification::query()
            ->when(request()->has('name'), function ($query) {
                $query->where('name', 'like', '%' . request()->name . '%');                
            })
            ->get();

            return response()->success('Data retrieved successfully.', $maritime_certifications, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

}
