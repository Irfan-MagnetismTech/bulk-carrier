<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Entities\OpsPort;
use Modules\Operations\Http\Requests\OpsPortRequest;


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

            return response()->json([
                'value'   => $ports,
                'message' => 'Successfully retrieved ports.',
            ], 200);
        }
        catch (QueryException $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
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
            $ports = OpsPort::create($request->all());

            return response()->json([
                'value' => $ports,
                'message' => 'Port added Successfully.'
            ], 201);
        }
        catch (QueryException $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  OpsPort  $port
     * @return \Illuminate\Http\Response
     */
    public function show(OpsPort $port): JsonResponse
    {
        try {
            return response()->json([
                'value' => $port,
                'message' => 'Successfully retrieved port.'
            ], 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
            // return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsPortRequest  $request
     * @param  OpsPort  $port
     * @return \Illuminate\Http\Response
     */
    public function update(OpsPortRequest $request, OpsPort $port): JsonResponse
    {
        // dd($request);
        try {
            $port->update($request->all());

            return response()->json([
                'value' => $port,
                'message' => 'Port updated Successfully.'
            ], 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
            // return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
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
            // return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
