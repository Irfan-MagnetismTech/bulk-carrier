<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsChartererProfile;
use Modules\Operations\Http\Requests\OpsChartererProfileRequest;

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
            $charterer_profiles = OpsChartererProfile::with('opsChartererBankAccounts')->latest()->paginate(15);
            
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
                 'opsChartererBankAccounts',
             );
 
             $charterer_profile = OpsChartererProfile::create($charterer_profile_info);
             $charterer_profile->opsChartererBankAccounts()->createMany($request->opsChartererBankAccounts);
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
      * @param  OpsChartererProfile $charterer_profile
      * @return JsonResponse
      */
     public function show(OpsChartererProfile $charterer_profile): JsonResponse
     {
         $charterer_profile->load('opsChartererBankAccounts');
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
                 'opsChartererBankAccounts',
             );
            
             $charterer_profile->update($charterer_profile_info);            
             $charterer_profile->opsChartererBankAccounts()->delete();
             $charterer_profile->opsChartererBankAccounts()->createMany($request->opsChartererBankAccounts);
             DB::commit();
             return response()->success('Charterer profile updated successfully.', $charterer_profile, 200);
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
      * @param  OpsChartererProfile  $charterer_profile
      * @return \Illuminate\Http\JsonResponse
      */
     public function destroy(OpsChartererProfile $charterer_profile): JsonResponse
     {
         try
         {
             $charterer_profile->opsChartererBankAccounts()->delete();
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
 
     public function getChartererProfileByNameorCode(Request $request){
         try {
             $charterer_profiles = OpsChartererProfile::query()
             ->where(function ($query) use($request) {
                 $query->where('name', 'like', '%' . $request->name_or_code . '%');
                 $query->orWhere('owner_code', 'like', '%' . $request->name_or_code . '%');
             })
             ->limit(10)
             ->get();
             return response()->success('Successfully retrieved charterer profile name.', $charterer_profiles, 200);
         } catch (QueryException $e){
             return response()->error($e->getMessage(), 500);
         }
     }
 
}
