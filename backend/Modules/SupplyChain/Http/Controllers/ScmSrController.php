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
use Modules\SupplyChain\Entities\ScmSrLine;
use Modules\SupplyChain\Services\CurrentStock;

class ScmSrController extends Controller
{
    function __construct()
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
    public function index(Request $request): JsonResponse
    {
        try {
            $storeRequisitions = ScmSr::with('scmSrLines.scmMaterial', 'scmWarehouse', 'createdBy', 'scmSis')
                ->globalSearch($request->all());

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

        // $requestData['ref_no'] = UniqueId::generate(ScmSr::class, 'SR');

        try {
            DB::beginTransaction();

            $scmSr = ScmSr::create($requestData);
            $linesData = CompositeKey::generateArray($request->scmSrLines, $scmSr->id, 'scm_material_id', 'sr');
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
            DB::beginTransaction();

            $storeRequisition->update($request->all());

            $storeRequisition->scmSrLines()->delete();

            $linesData = CompositeKey::generateArray($request->scmSrLines, $storeRequisition->id, 'scm_material_id', 'sr');

            $storeRequisition->scmSrLines()->createMany($linesData);

            DB::commit();

            return response()->success('Data updated sucessfully!', $storeRequisition, 202);
        } catch (\Exception $e) {
            DB::rollBack();

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
        if ($storeRequisition->scmSis()->count() > 0) {
            $error = [
                "message" => "Data could not be deleted!",
                "errors" => [
                    "id" => ["This data could not be deleted as it has reference to other table"]
                ]
            ];
            return response()->json($error, 422);
        }

        try {
            DB::beginTransaction();

            $storeRequisition->scmSrLines()->delete();
            $storeRequisition->delete();

            DB::commit();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function getMaterialBySrId(): JsonResponse
    {
        $srMaterials = ScmSrLine::query()
            ->with('scmMaterial', 'scmSr')
            ->where('scm_sr_id', request()->sr_id)
            ->get()
            ->map(function ($item) {
                $currentStock = CurrentStock::count($item->scm_material_id, $item->scmSr->scm_warehouse_id);
                $srQty = $item->quantity - $item->scmSiLines->sum('quantity');
                if (request()->si_id) {
                    $data1 = $item->scmSiLines->where('scm_si_id', request()->si_id)->where('sr_composite_key', $item->sr_composite_key)->first()->quantity ?? 0;
                    $cStock = $currentStock + $data1;
                    $srQty = $srQty + $data1;
                    $maxQty = $cStock > $srQty ? $srQty : $cStock;
                } else {
                    $maxQty = $currentStock > $srQty ? $srQty : $currentStock;
                }

                $data = $item->scmMaterial;
                $data['unit'] = $item->unit;
                $data['sr_quantity'] = $item->quantity;
                $data['quantity'] = $item->quantity;
                $data['current_stock'] = $currentStock;
                $data['max_quantity'] = $maxQty;
                $data['sr_composite_key'] = $item->sr_composite_key;
                $data['remaining_quantity'] = $item->quantity - $item->scmSiLines->sum('quantity');

                return $data;
            });

        return response()->success('data list', $srMaterials, 200);
    }
}
