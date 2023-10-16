<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\SupplyChain\Entities\ScmUnit;
use Modules\SupplyChain\Http\Requests\ScmUnitRequest;

class ScmUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scm_units = ScmUnit::latest()->paginate(10);

            return response()->success('Unit list', $scm_units, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmUnitRequest $request)
    {
        try {
            $scm_unit = ScmUnit::create($request->all());

            return response()->success('Unit created succesfully', $scm_unit, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmUnit $unit
     * @return JsonResponse
     */
    public function show(ScmUnit $unit): JsonResponse
    {
        try {
            return response()->success('data', $unit, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmUnitRequest $request
     * @param ScmUnit $unit
     * @return JsonResponse
     */
    public function update(ScmUnitRequest $request, ScmUnit $unit)
    {
        try {
            $unit->update($request->all());

            return response()->success('Data updated sucessfully!', $unit, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmUnit $unit
     * @return JsonResponse
     */
    public function destroy(ScmUnit $unit): JsonResponse
    {
        try {
            $unit->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
