<?php

namespace Modules\Operations\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsPort;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Http\Requests\OpsPortRequest;

class OpsPortController extends Controller
{
    use HasRoles;

    function __construct()
    {
        $this->middleware('permission:ops-port-create|ops-port-edit|ops-port-view|ops-port-delete', ['only' => ['index','show']]);
        $this->middleware('permission:ops-port-create', ['only' => ['store']]);
        $this->middleware('permission:ops-port-edit', ['only' => ['update']]);
        $this->middleware('permission:ops-port-delete', ['only' => ['destroy']]);
    }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            // dd($request->all());
            $ports = OpsPort::globalSearch($request->all());
            
            return response()->success('Data retrieved successfully.', $ports, 200);
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
            return response()->success('Data added successfully.', $ports, 201);
        }
        catch (Exception $e)
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
            return response()->success('Data retrieved successfully.', $port, 200);
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
            return response()->success('Data updated Successfully.', $port, 202);
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
            DB::beginTransaction();            
            $port->delete($port);
            DB::commit();
            
            return response()->json([
                'value' => '',
                'message' => 'Data deleted Successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);      
        }
    }


    public function getPortByNameOrCode(Request $request){
        try {
            $ports = OpsPort::query()
                ->when(isset(request()->name_or_code), function ($query) {
                    $query->where('name', 'like', '%' . request()->name_or_code . '%');
                    $query->orWhere('code', 'like', '%' . request()->name_or_code . '%');
                })
                ->when(isset($request->business_unit) && $request->business_unit != "ALL", function ($q) use ($request) {
                    $q->where('business_unit', $request->business_unit);
                })
                ->limit(10)
                ->get();

            return response()->success('Data retrieved successfully.', $ports, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
    
    public function getPortNameOrCode(Request $request){
        try {
            $ports = OpsPort::query()
            ->when(isset(request()->name_or_code), function ($query) {
                $query->where('name', 'like', '%' . request()->name_or_code . '%');
                $query->orWhere('code', 'like', '%' . request()->name_or_code . '%');
            })
            ->get();

            return response()->success('Data retrieved successfully.', $ports, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }
}
