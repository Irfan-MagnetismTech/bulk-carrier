<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Http\Requests\OpsPortRequest;
use Illuminate\Support\Facades\DB;

class OpsPortController extends Controller
{
    // use HasRoles;

    // function __construct()
    // {
    //     $this->middleware('permission:port-create|port-edit|port-show|port-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:port-create', ['only' => ['store']]);
    //     $this->middleware('permission:port-edit', ['only' => ['update']]);
    //     $this->middleware('permission:port-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $ports = OpsPort::latest()->paginate(15);
            // if($request->searchKey != null) {
            //     // $ports = $this->globalSearch($request->all());
            // } else {
            //     $ports = tap(OpsPort::query()->paginate(15), function ($paginatedInstance){
            //         return $paginatedInstance->getCollection()->transform(function ($permission){
            //             $permission->subject_type = base64_encode(get_class($permission));
            //             return $permission;
            //         });
            //     }
            //     );
            // }
            return response()->success('Successfully retrieved ports.', $ports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }


    
    /**
     * Store a newly created resource in storage.
     * @param OpsPortRequest $request
     * @return JsonResponse
     */
    public function store(OpsPortRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $ports = OpsPort::create($request->all());
            DB::commit();   
            return response()->success('Port added Successfully.', $ports, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified port.
     *
     * @param  OpsPort  $port
     * @return JsonResponse
     */
    public function show(OpsPort $port): JsonResponse
    {
        try {            
            return response()->success('Successfully retrieved port.', $port, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsPortRequest  $request
     * @param  OpsPort  $port
     * @return JsonResponse
     */
    public function update(OpsPortRequest $request, OpsPort $port): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $port->update($request->all());
            DB::commit();               
            return response()->success('Port updated Successfully.', $port, 202);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsPort  $port
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsPort $port): JsonResponse
    {
        try {
            $port->delete($port);

            return response()->json([
                'value' => '',
                'message' => 'Port deleted Successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);        
        }
    }

    public function getPortName(){
        try {
            $ports = OpsPort::all();
            return response()->success('Successfully retrieved ports name.', collect($ports->pluck('name'))->unique()->values()->all(), 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getPortWithoutPaginate(){
        try
        {
            $ports = OpsPort::all();            
            return response()->success('Successfully retrieved ports for without paginate.', $ports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
