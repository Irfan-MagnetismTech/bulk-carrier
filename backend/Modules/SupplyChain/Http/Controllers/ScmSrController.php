<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmSr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Http\Requests\ScmSrRequest;

class ScmSrController extends Controller
{
    function __construct(private UniqueId $uniqueId, private CompositeKey $compositeKey)
    {
        //     $this->middleware('permission:charterer-contract-create|charterer-contract-edit|charterer-contract-show|charterer-contract-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:charterer-contract-create', ['only' => ['store']]);
        //     $this->middleware('permission:charterer-contract-edit', ['only' => ['update']]);
        //     $this->middleware('permission:charterer-contract-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $storeRequisitions = ScmSr::with('scmSrLines.scmMaterial', 'scmWarehouse', 'createdBy')->latest()->paginate(10);

            return response()->success('Data list', $storeRequisitions, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmSrRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no', 'sr_composite_key');

        $requestData['ref_no'] = $this->uniqueId->generate(ScmSr::class, 'SR');



        try {
            DB::beginTransaction();

            $scmSr = ScmSr::create($requestData);

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmSrLines, $scmSr->id, 'scm_material_id', 'sr');

            $scmSr->scmSrLines()->createMany($linesData);

            DB::commit();

            return response()->success('Data created succesfully', $scmSr, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmSr $storeRequisition
     * @return JsonResponse
     */
    public function show(ScmSr $storeRequisition): JsonResponse
    {
        try {
            return response()->success('data', $storeRequisition->load('scmSrLines.scmMaterial', 'scmWarehouse', 'createdBy'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmSrRequest $request
     * @param ScmSr $storeRequisition
     * @return JsonResponse
     */
    public function update(ScmSrRequest $request, ScmSr $storeRequisition): JsonResponse
    {
        try {
            $storeRequisition->update($request->all());

            $storeRequisition->scmSrLines()->delete();

            $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmSrLines, $storeRequisition->id, 'scm_material_id', 'sr');

            $storeRequisition->scmSrLines()->createMany($linesData);

            return response()->success('Data updated sucessfully!', $storeRequisition, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmSr $storeRequisition
     * @return JsonResponse
     */
    public function destroy(ScmSr $storeRequisition): JsonResponse
    {
        try {
            $storeRequisition->scmSrLines()->delete();
            $storeRequisition->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchVendor(Request $request): JsonResponse
    {
        $storeRequisitions = ScmSr::query()
            ->with('scmSrLines')
            ->where('name', 'like', "%$request->searchParam%")
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $storeRequisitions, 200);
    }
}