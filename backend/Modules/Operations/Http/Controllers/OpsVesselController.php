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
    use HasRoles; 

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
            $vessel = OpsVessel::orderBy('id','DESC')->Paginate();

            return response()->json([
                'value'   => $vessel,
                'message' => 'Successfully retrieved vessels.',
            ], 200);
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
            $vessel = OpsVessel::create($request->all());
            DB::commit();
            
            return response()->json([
                'value'   => $vessel,
                'message' => 'Successfully created vessel.',
            ], 201);
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
        // dd($vessel);
        try
        {
            return response()->json([
                'value'   => $vessel,
                'message' => 'Successfully retrieved vessel.',
            ], 200);
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
        try
        {
            DB::beginTransaction();
            $vessel->update($request->all());
            DB::commit();
            
            return response()->json([
                'message' => 'Successfully updated vessel.',
            ], 202);
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

            return response()->json([
                'value'   => $vessel,
                'message' => 'Successfully retrieved vessels.',
            ], 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    
    public function getVesselName(){
        try {
            $vessels = OpsVessel::all();
            return response()->json([
                'value' => collect($vessels->pluck('name'))->unique()->values()->all(),
                'message' => 'Successfully retrieved vessels name.'
            ], 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getVesselWithoutPaginate(){
        try
        {
            $vessels = OpsVessel::all();
            return response()->json([
                'value'   => $vessels,
                'message' => 'Successfully retrieved vessels.',
            ], 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
