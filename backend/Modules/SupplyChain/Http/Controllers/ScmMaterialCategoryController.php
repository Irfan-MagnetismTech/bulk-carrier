<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\QueryException;
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
            $scm_material_categories = ScmMaterialCategory::with('parent')
                ->globalSearch(request()->all());

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
            $material_category = ScmMaterialCategory::create($request->all());

            return response()->success('Data created succesfully', $material_category, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMaterialCategory $material_category
     * @return JsonResponse
     */
    public function show(ScmMaterialCategory $material_category): JsonResponse
    {
        try {
            return response()->success('data', $material_category->load('parent'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMaterialCategoryRequest $request
     * @param ScmMaterialCategory $material_category
     * @return JsonResponse
     */
    public function update(ScmMaterialCategoryRequest $request, ScmMaterialCategory $material_category): JsonResponse
    {
        try {
            $material_category->update($request->all());

            return response()->success('Data updated sucessfully!', $material_category->load('parent'), 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMaterialCategory $material_category
     * @return JsonResponse
     */
    public function destroy(ScmMaterialCategory $material_category): JsonResponse
    {
        try {
            // if (count($material_category->children) > 0) {
            //     return response()->error('Category has Children', 500);
            // }
            //if id is 1 then return error
            if ($material_category->id === 1) {

                return response()->error('Category cannot be deleted', 501);
            }
            $material_category->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (QueryException $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchMaterialCategory(): JsonResponse
    {
        $materialCategory = ScmMaterialCategory::query()
            ->with('parent')
            ->when(request()->has('self_id'), function ($query) {
                $query->where('id', '!=', request()->self_id);
            })
            // ->when(request()->has('searchParam'), function ($query) {
            //     $query->where(function ($subquery) {
            //         $subquery->where('name', 'like',  "%" . request()->searchParam . "%")
            //             ->orWhere('short_code', 'like',  "%" . request()->searchParam . "%");
            //     });
            // })
            ->orderByDesc('name')
            // ->limit(10)
            ->get();

        return response()->success('Search result', $materialCategory, 200);
    }
}
