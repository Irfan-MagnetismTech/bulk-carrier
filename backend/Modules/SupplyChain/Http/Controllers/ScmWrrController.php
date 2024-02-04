<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Services\FileUploadService;
use Modules\SupplyChain\Entities\ScmWrr;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Http\Requests\ScmWrrRequest;

class ScmWrrController extends Controller
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
            $wrrs = ScmWrr::globalSearch(request()->all());

            return response()->success('Material Category list', $wrrs, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmWrrRequest $request): JsonResponse
    {
        $requestData = $request->all();
        try {
            $wrr = ScmWrr::create($requestData);
            return response()->success('Data created succesfully', $wrr, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function show(ScmWrr $wrr): JsonResponse
    {
        try {
            return response()->success('data', $wrr, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmWrrRequest $request
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function update(ScmWrrRequest $request, ScmWrr $wrr): JsonResponse
    {
        $requestData = $request->all();
        try {            
            $wrr->update($requestData);
            return response()->success('Data updated sucessfully!', $wrr, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function destroy(ScmWrr $wrr): JsonResponse
    {
        try {
            $wrr->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
