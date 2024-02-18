<?php

namespace Modules\SupplyChain\Http\Controllers;

use Nwidart\Modules\Facades\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmMaterialCategory;
use Modules\SupplyChain\Http\Requests\ScmMaterialCategoryRequest;

class ScmMaterialCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:scm-material-category-view|scm-material-category-create|scm-material-category-edit|scm-material-category-delete|scm-material-category-close', ['only' => ['index', 'show']]);
        $this->middleware('permission:scm-material-category-create', ['only' => ['store']]);
        $this->middleware('permission:scm-material-category-edit', ['only' => ['update']]);
        $this->middleware('permission:scm-material-category-delete', ['only' => ['destroy']]);
    }
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

        $category = ScmMaterialCategory::find($request->parent_id);

        if ($category && $category->getLayerCount() >= 2) {
            $error = [
                "message" => "Data could not be created!",
                "errors" => [
                    "id" => ["Category cannot have more than two layers!"],
                ]
            ];
            return response()->json($error, 422);
        }

        try {
            $material_category = ScmMaterialCategory::create($request->all());
            // for account creation start
            if ($request->parent_id == null || $request->parent_id == "") {
                $psmlData = [
                    'acc_balance_and_income_line_id' => config('accounts.balance_income_line.inventory'),
                    'account_name' => $material_category->name,
                    'account_code' => config('accounts.account_types.Assets') . ' - ' . config('accounts.balance_income_balance_header.current_assets') . ' - ' . config('accounts.balance_income_line.inventory') . ' - ' . $material_category->id,
                    // 'account_code'=> "config('accounts.account_types.Assets') - 5 - config('accounts.balance_income_line.inventory') - $material_category->id",
                    'account_type' => config('accounts.account_types.Assets'),
                    'business_unit' => 'PSML',
                ];

                $tsllData = [
                    'acc_balance_and_income_line_id' => config('accounts.balance_income_line.inventory'),
                    'account_name' => $material_category->name,
                    'account_code' => config('accounts.account_types.Assets') . ' - ' . config('accounts.balance_income_balance_header.current_assets') . ' - ' . config('accounts.balance_income_line.inventory') . ' - ' . $material_category->id,
                    // 'account_code'=> "config('accounts.account_types.Assets') - 5 - config('accounts.balance_income_line.inventory') - $material_category->id",
                    'account_type' => config('accounts.account_types.Assets'),
                    'business_unit' => 'TSLL',
                ];

                $material_category->account()->create($psmlData);
                $material_category->account()->create($tsllData);
            }

            // for account creation end
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
     * @param ScmMaterialCategory $materialCategory
     * @return JsonResponse
     */
    public function destroy(ScmMaterialCategory $materialCategory): JsonResponse
    {
        try {
            // if (count($materialCategory->children) > 0) {
            //     return response()->error('Category has Children', 500);
            // }
            //if id is 1 then return error
            if ($materialCategory->id === 1) {

                return response()->error('Category cannot be deleted', 501);
            }
            $materialCategory->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (QueryException $e) {

            return response()->json($materialCategory->preventDeletionIfRelated(), 422);
        }
    }

    public function searchMaterialCategory(): JsonResponse
    {
        $materialCategory = ScmMaterialCategory::query()
            // ->with('parent')
            ->leafNodes()
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
