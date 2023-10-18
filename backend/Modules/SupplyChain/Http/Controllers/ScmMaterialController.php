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
            return response()->success('data', $material, 200);
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
        try {
            $material->update($request->all());

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
            $material->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
