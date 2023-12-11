<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsHandoverTakeover;
use Modules\Operations\Http\Requests\OpsHandoverTakeoverRequest;

class OpsHandoverTakeoverController extends Controller
{
  //    use HasRoles;
 
//    function __construct(private FileUploadService $fileUpload)
//    {
//        $this->middleware('permission:handover-takeover-create|handover-takeover-edit|handover-takeover-show|handover-takeover-delete', ['only' => ['index','show']]);
//        $this->middleware('permission:handover-takeover-create', ['only' => ['store']]);
//        $this->middleware('permission:handover-takeover-edit', ['only' => ['update']]);
//        $this->middleware('permission:handover-takeover-delete', ['only' => ['destroy']]);
//    }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    **/
    public function index(Request $request): JsonResponse
    {
        try {
            $handover_takeovers = OpsHandoverTakeover::with('opsChartererProfile','opsVessel','opsBunkers.scmMaterial')
            ->globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $handover_takeovers, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
    * 
    * @param OpsHandoverTakeoverRequest $request
    * @return JsonResponse
    */
    public function store(OpsHandoverTakeoverRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $handover_takeover_info = $request->except(
                '_token',
                'opsBunkers',
            );
            $check= OpsHandoverTakeover::where('ops_vessel_id', $request->ops_vessel_id)->latest()->first();
            dd($check);
            if($check->note_type == $request->note_type){
                $error= [
                        'message'=>'You can not perform this action. This vessel is in '.strtolower($request->note_type). ' status.',
                        'errors'=>[
                            'note_type'=>['You can not perform this action. This vessel is in '.strtolower($request->note_type). ' status.',
                ]]];
                return response()->json($error, 422);
            }
            
            $handover_takeover = OpsHandoverTakeover::create($handover_takeover_info);            
            $handover_takeover->opsBunkers()->createMany($request->opsBunkers);
            DB::commit();
            return response()->success('Data added successfully.', $handover_takeover, 201);
        }
        catch (QueryException $e)
        {
            // dd($e->getMessage());
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }
 
    /**
     * Display the specified maritime certification.
    *
    * @param  OpsHandoverTakeover  $handover_takeover
    * @return JsonResponse
    */
    public function show(OpsHandoverTakeover $handover_takeover): JsonResponse
    {
        $handover_takeover->load('opsChartererProfile','opsVessel','opsBunkers.scmMaterial');
        $handover_takeover->opsBunkers->map(function($bunker) {
            $bunker->name = $bunker->scmMaterial->name;
            return $bunker;
        });
        
        try
        {
            return response()->success('Data retrieved successfully.', $handover_takeover, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 
 
    /**
     * Update the specified resource in storage.
    *
    * @param OpsHandoverTakeoverRequest $request
    * @param  OpsHandoverTakeover  $handover_takeover
    * @return JsonResponse
    */
    public function update(OpsHandoverTakeoverRequest $request, OpsHandoverTakeover $handover_takeover): JsonResponse
    {
    try {
        DB::beginTransaction();
        $handover_takeover_info = $request->except(
            '_token',
            'opsBunkers',
        );

        $handover_takeover->update($handover_takeover_info);  
        $handover_takeover->opsBunkers()->delete();
        $handover_takeover->opsBunkers()->createMany($request->opsBunkers);

        DB::commit();
        return response()->success('Data updated successfully.', $handover_takeover, 202);
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
      * @param  OpsHandoverTakeover  $handover_takeover
      * @return \Illuminate\Http\JsonResponse
      */
    public function destroy(OpsHandoverTakeover $handover_takeover): JsonResponse
    {
        try
        {
            $handover_takeover->opsBunkers()->delete();
            $handover_takeover->delete();

            return response()->json([
                'message' => 'Data deleted successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    // public function getHandoverTakeoverName(){
    //     try {
    //         $handover_takeovers = OpsHandoverTakeover::all();
    //         return response()->success('Successfully retrieved handover takeovers name.', collect($voyages->pluck('route'))->unique()->values()->all(), 200);
    //     } catch (QueryException $e){
    //         return response()->error($e->getMessage(), 500);
    //     }
    // }


}
