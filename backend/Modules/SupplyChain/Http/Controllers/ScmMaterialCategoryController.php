<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmMaterialCategory;
use Modules\SupplyChain\Http\Requests\ScmMaterialCategoryRequest;

class ScmMaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
      * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scm_material_categories = ScmMaterialCategory::latest()->paginate(10);

            return response()->success('Material Category list', $scm_material_categories, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmMaterialCategoryRequest $request): JsonResponse
    {
        try {
            $scm_material_category = ScmMaterialCategory::create($request->all());

            return response()->json([
                'status' => 'success',
                'value' => $scm_material_category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMaterialCategory $materialCategory
     * @return JsonResponse
     */
    public function show(ScmMaterialCategory $materialCategory): JsonResponse
    {
        try {
            return response()->json([
                'status' => 'success',
                'value' => $materialCategory,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMaterialCategoryRequest $request
     * @param ScmMaterialCategory $materialCategory
     * @return JsonResponse
     */
    public function update(ScmMaterialCategoryRequest $request, ScmMaterialCategory $materialCategory): JsonResponse
    {
        try {
            $materialCategory->update($request->all());

            return response()->json([
                'status' => 'success',
                'value' => $materialCategory,
            ], 202);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMaterialCategory $materialCategory
     * @return JsonResponse
     */
    public function destroy(ScmMaterialCategory $materialCategory): JsonResponse
    {
        try {
            $materialCategory->delete();

            return response()->json([
                'status' => 'success',
                'data' => null,
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function searchMaterialCategory(Request $request)
    {
        $materialCategory = ScmMaterialCategory::where('name', 'like', "%$request->searchParam%")->orderBy('name')->limit(10)->get();

        return response()->success('Unit Name', $materialCategory, 200);
    }
}
