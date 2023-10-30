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
    public function index()
    {
        try {
            $handover_takeovers = OpsHandoverTakeover::with('opsChartererProfile','opsVessel','opsBunkers')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved handover takeovers.', $handover_takeovers, 200);
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

        $handover_takeover = OpsHandoverTakeover::create($handover_takeover_info);            
        $handover_takeover->opsBunkers()->createMany($request->opsBunkers);
        DB::commit();
        return response()->success('Handover takeover added successfully.', $handover_takeover, 201);
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
    * @param  OpsHandoverTakeover  $handover_takeover
    * @return JsonResponse
    */
    public function show(OpsHandoverTakeover $handover_takeover): JsonResponse
    {
    $handover_takeover->load('opsChartererProfile','opsVessel','opsBunkers');
    try
    {
        return response()->success('Successfully retrieved handover takeover.', $handover_takeover, 200);
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
        return response()->success('Handover takeover updated successfully.', $voyage, 200);
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
                'message' => 'Successfully deleted handover takeover.',
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
