<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsChartererProfile;
use Modules\Operations\Http\Requests\OpsChartererProfileRequest;
use Illuminate\Support\Facades\DB;

class OpsChartererProfileController extends Controller
{
    // use HasRoles;
    
//    function __construct(private FileUploadService $fileUpload)
//    {
   //     $this->middleware('permission:charterer-profile-create|charterer-profile-edit|charterer-profile-show|charterer-profile-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:charterer-profile-create', ['only' => ['store']]);
   //     $this->middleware('permission:charterer-profile-edit', ['only' => ['update']]);
   //     $this->middleware('permission:charterer-profile-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index()
    {
        try {
            $charterer_profiles = OpsChartererProfile::with('ops_charterer_bank_accounts')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved charterer profiles.', $charterer_profiles, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
        /**
      * Store a newly created resource in storage.
      * 
      * @param OpsChartererProfileRequest $request
      * @return JsonResponse
     */
     public function store(OpsChartererProfileRequest $request): JsonResponse
     {
         // dd($request);
         try {
             DB::beginTransaction();
             $charterer_profile_info = $request->except(
                 '_token',
                 'ops_charterer_bank_accounts',
             );
 
             $charterer_profile = OpsCargoTariff::create($charterer_profile_info);
             $charterer_profile->ops_charterer_bank_accounts()->createMany($request->ops_charterer_bank_accounts);
             DB::commit();
             return response()->success('Charterer profile added successfully.', $charterer_profile, 201);
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
      * @param  OpsChartererProfile $cargo_tariff
      * @return JsonResponse
      */
     public function show(OpsChartererProfile $charterer_profile): JsonResponse
     {
         $charterer_profile->load('ops_charterer_bank_accounts');
         try
         {
             return response()->success('Successfully retrieved charterer profile.', $charterer_profile, 200);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
 
     }
 
 
       /**
      * Update the specified resource in storage.
      *
      * @param OpsChartererProfileRequest $request
      * @param  OpsChartererProfile  $charterer_profile
      * @return JsonResponse
      */
     public function update(OpsChartererProfileRequest $request, OpsChartererProfile $charterer_profile): JsonResponse
     {
         try {
             DB::beginTransaction();
             $charterer_profile_info = $request->except(
                 '_token',
                 'ops_charterer_bank_accounts',
             );
            
             $charterer_profile->update($charterer_profile_info);            
             $charterer_profile->ops_charterer_bank_accounts()->delete();
             $charterer_profile->ops_charterer_bank_accounts()->createMany($request->ops_charterer_bank_accounts);
             DB::commit();
             return response()->success('Charterer profile updated successfully.', $cargo_tariff, 200);
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
      * @param  OpsChartererProfile  $cargo_tariff
      * @return \Illuminate\Http\JsonResponse
      */
     public function destroy(OpsChartererProfile $charterer_profile): JsonResponse
     {
         try
         {
             $charterer_profile->ops_charterer_bank_accounts()->delete();
             $charterer_profile->delete();
 
             return response()->json([
                 'message' => 'Successfully deleted charterer profile.',
             ], 204);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
     }
 
     public function getCargoTariffName(){
         try {
             $charterer_profiles = OpsChartererProfile::all();
             return response()->success('Successfully retrieved charterer profile name.', collect($charterer_profiles->pluck('name'))->unique()->values()->all(), 200);
         } catch (QueryException $e){
             return response()->error($e->getMessage(), 500);
         }
     }
 
     public function getCargoTariffWithoutPaginate(){
         try
         {
             $charterer_profiles = OpsChartererProfile::with('ops_charterer_bank_accounts')->latest()->get();       
             return response()->success('Successfully retrieved charterer profiles for without paginate.', $charterer_profiles, 200);
         }
         catch (QueryException $e)
         {
             return response()->error($e->getMessage(), 500);
         }
     }
}
