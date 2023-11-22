<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsCargoType;
use Modules\Operations\Http\Requests\OpsCargoTypeRequest;

class OpsCargoTypeController extends Controller
{
   // use HasRoles;

    // function __construct()
    // {
    //     $this->middleware('permission:cargo-type-create|cargo-type-edit|cargo-type-show|cargo-type-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:cargo-type-create', ['only' => ['store']]);
    //     $this->middleware('permission:cargo-type-edit', ['only' => ['update']]);
    //     $this->middleware('permission:cargo-type-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $cargo_types = OpsCargoType::globalSearch($request->all());

            return response()->success('Data retrieved successfully.', $cargo_types, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param OpsCargoTypeRequest $request
     * @return JsonResponse
     */
    public function store(OpsCargoTypeRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $cargo_type = OpsCargoType::create($request->all());
            DB::commit();
            return response()->success('Data added successfully.', $cargo_type, 201);
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
     * @param  OpsCargoType  $cargo_type
     * @return JsonResponse
     */
    public function show(OpsCargoType $cargo_type): JsonResponse
    {
        try {
            return response()->success('Data retrieved successfully.', $cargo_type, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsCargoTypeRequest  $request
     * @param  OpsCargoType  $cargo_type
     * @return JsonResponse
     */
    public function update(OpsCargoTypeRequest $request, OpsCargoType $cargo_type): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $cargo_type->update($request->all());
            DB::commit();
            return response()->success('Data updated successfully.', $cargo_type, 202);
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
     * @param  OpsCargoType  $port
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsCargoType $cargo_type): JsonResponse
    {
        try {
            $cargo_type->delete($cargo_type);

            return response()->json([
                'message' => 'Data deleted successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCargoTypeByName(Request $request){
        try {
            $cargo_types = OpsCargoType::query()
            ->where(function ($query) use($request) {
                $query->where('cargo_type', 'like', '%' . $request->cargo_type . '%');
            })
            ->limit(10)
            ->get();

            return response()->success('Data retrieved successfully.', $cargo_types, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

    public function getCargoTypeName(Request $request){
        try {
            $cargo_types = OpsCargoType::query()
            ->where(function ($query) use($request) {
                $query->where('cargo_type', 'like', '%' . $request->cargo_type . '%');
            })
            ->get();

            return response()->success('Data retrieved successfully.', $cargo_types, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }

}
