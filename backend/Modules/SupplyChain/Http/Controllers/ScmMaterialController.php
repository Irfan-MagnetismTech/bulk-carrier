<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Services\FileUploadService;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Http\Requests\ScmMaterialRequest;

class ScmMaterialController extends Controller
{
    // use HasRoles;

    function __construct(private FileUploadService $fileUpload)
    {
        //     $this->middleware('permission:charterer-contract-create|charterer-contract-edit|charterer-contract-show|charterer-contract-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:charterer-contract-create', ['only' => ['store']]);
        //     $this->middleware('permission:charterer-contract-edit', ['only' => ['update']]);
        //     $this->middleware('permission:charterer-contract-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): JsonResponse
    {
        try {
            $scm_material_categories = ScmMaterial::with('scmMaterialCategory')->latest()->paginate(10);

            return response()->success('Material Category list', $scm_material_categories, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmMaterialRequest $request)
    {
        $requestData = $request->all();
        try {
            if (isset($request->sample_photo)) {
                $sample_photos = $this->fileUpload->handleFile($request->sample_photo, 'scm/materials');
                $requestData['sample_photo'] = $sample_photos;
            }
            $material = ScmMaterial::create($requestData);

            return response()->success('Data created succesfully', $material, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmMaterial $material
     * @return JsonResponse
     */
    public function show(ScmMaterial $material): JsonResponse
    {
        try {
            return response()->success('data', $material->load('scmMaterialCategory'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmMaterialRequest $request
     * @param ScmMaterial $material
     * @return JsonResponse
     */
    public function update(ScmMaterialRequest $request, ScmMaterial $material)
    {
        $requestData = $request->all();

        try {
            if (isset($request->sample_photo)) {
                $sample_photos = $this->fileUpload->handleFile($request->sample_photo, 'scm/materials', $material->sample_photo);
                $requestData['sample_photo'] = $sample_photos;
            }
            $material->update($requestData);

            return response()->success('Data updated sucessfully!', $material, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmMaterial $material
     * @return JsonResponse
     */
    public function destroy(ScmMaterial $material): JsonResponse
    {
        try {
            $this->fileUpload->deleteFile($material->sample_photo);
            $material->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchMaterial(Request $request)
    {
        $searchParam = $request->searchParam;

        $materialCategory = ScmMaterial::query()
            ->where(function ($query) use ($searchParam) {
                $query->where('name', 'like', "%$searchParam%")
                    ->orWhere('material_code', 'like', "%$searchParam%");
            })
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $materialCategory, 200);
    }

    public function searchMaterialByCategory()
    {
        $materialCategory = ScmMaterial::query()
            ->when(request()->scmMaterialCategoryId, function ($query) {
                $query->where('scm_material_category_id', request()->scmMaterialCategoryId)
                    ->where(function ($query2) {
                        $query2->where('name', 'like', "%" . request()->searchParam . "%")
                            ->orWhere('material_code', 'like', "%" . request()->searchParam . "%");
                    });
            })
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $materialCategory, 200);
    }
}
