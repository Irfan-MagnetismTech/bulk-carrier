<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\SupplyChain\Entities\ScmUnit;
use Modules\SupplyChain\Http\Requests\ScmUnitRequest;

class ScmUnitController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:scm-unit-view|scm-unit-create|scm-unit-edit|scm-unit-delete|scm-unit-close', ['only' => ['index', 'show']]);
        $this->middleware('permission:scm-unit-create', ['only' => ['store']]);
        $this->middleware('permission:scm-unit-edit', ['only' => ['update']]);
        $this->middleware('permission:scm-unit-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $scm_units = ScmUnit::query()
                ->globalSearch($request->all());

            return response()->success('Data list', $scm_units, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmUnitRequest $request): JsonResponse
    {
        try {
            $scm_unit = ScmUnit::create($request->all());

            return response()->success('Data created succesfully', $scm_unit, 201);
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
    public function update(ScmUnitRequest $request, ScmUnit $unit): JsonResponse
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

    /**
     * Search for units based on the provided search parameter.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function searchUnit(Request $request): JsonResponse
    {
        $materialCategory = ScmUnit::query()
            // ->where('name', 'like', "%{$request->searchParam}%")
            ->orderByDesc('name')
            // ->limit(10)
            ->get();

        return response()->success('Search result', $materialCategory, 200);
    }
}
