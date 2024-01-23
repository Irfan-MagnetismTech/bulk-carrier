<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmWo;
use Modules\SupplyChain\Entities\ScmWr;
use Modules\SupplyChain\Entities\ScmWcs;
use Modules\SupplyChain\Services\UniqueId;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Http\Requests\ScmWoRequest;

class ScmWoController extends Controller
{
    function __construct()
    {
        //     $this->middleware('permission:work-order-create|work-order-edit|work-order-show|work-order-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:work-order-create', ['only' => ['store']]);
        //     $this->middleware('permission:work-order-edit', ['only' => ['update']]);
        //     $this->middleware('permission:work-order-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $scmWorkOrders = ScmWo::query()
                ->with('scmWoLines', 'scmWoTerms', 'scmVendor', 'scmWarehouse', 'scmWcs.scmWr')
                ->globalSearch($request->all());

            return response()->success('Data list', $scmWorkOrders, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ScmWoRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no');
        $requestData['ref_no'] = UniqueId::generate(ScmWo::class, 'WO');

        try {
            DB::beginTransaction();
            $scmWo = ScmWo::create($requestData);
            // $linesData = CompositeKey::generateArray($request->scmPoLines, $scmPo->id, 'scm_material_id', 'po');
            // $data = $this->addNetRateToRequestData($request, $scmWo->id);
            // $linesData = CompositeKey::generateArray($request->scmWrLines, $scmWo->id, 'scm_service_id', 'wo');
            // $scmWo->scmWoLines()->createUpdateOrDelete($linesData);
            $scmWo->scmWoTerms()->createUpdateOrDelete($request->scmWoTerms);
            DB::commit();
            return response()->success('Data created successfully', $scmWo, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supplychain::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supplychain::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    // public function addNetRateToRequestData($request, $wo_id): mixed
    // {
    //     $scmWoLines = $request['scmWoLines'];
    //     foreach ($scmWoLines as $key => $value) {
    //         $scmWoLines[$key]['wo_composite_key'] = CompositeKey::generate($key, $wo_id, 'wo', $value['scm_service_id']);
    //     }
    //     $request['scmWoLines'] = $scmWoLines;

    //     return $request;
    // }


    public function getWoOrWoCsWiseWrData(Request $request): JsonResponse
    {
        try {
            if ($request->scm_wcs_id == null) {
                $scmWr = ScmWr::query()
                    ->with([
                        'scmWarehouse',
                        'scmWrLines.scmService',
                    ])
                    ->where('id', $request->scm_wr_id)
                    ->first();

                $data = [
                    'scmWarehouse' => $scmWr->scmWarehouse,
                    'scm_warehouse_id' => $scmWr->scm_warehouse_id,
                    'wr_no' => $scmWr->ref_no,
                    'scm_wr_id' => $scmWr->id,
                    'scmWr' => $scmWr,
                    'pr_date' => $scmWr->raised_date,
                    'business_unit' => $scmWr->business_unit,
                    'purchase_center' => $scmWr->purchase_center,
                    'scmWoLines' => $scmWr->scmWrLines->map(function ($item) {
                        return [
                            'scmService' => $item->scmService,
                            'scm_service_id' => $item->scmService->id,
                            'quantity' => $item->quantity,
                            'wr_composite_key' => $item->wr_composite_key,
                            'max_quantity' => $item->quantity - $item->scmWoLines->sum('quantity'),
                            'rate' => 0,
                            // 'total_price' => $item->total_price
                        ];
                    })
                ];
            } else {
                $scmWcs = ScmWcs::query()
                    ->with('scmWarehouse', 'scmWr')
                    ->find('id', $request->scm_wcs_id);

                $data = [
                    'scmWarehouse' => $scmWcs->scmWarehouse,
                    'scm_warehouse_id' => $scmWcs->scm_warehouse_id,
                    'wr_no' => $scmWcs->scmWr->ref_no,
                    'wcs_no' => $scmWcs->ref_no,
                    'scm_wr_id' => $scmWcs->scmWr->id,
                    'scmWr' => $scmWcs->scmWr,
                    'wr_date' => $scmWcs->scmWr->raised_date,
                    'business_unit' => $scmWcs->scmWr->business_unit,
                    'purchase_center' => $scmWcs->scmWr->purchase_center,
                ];
            }

            return response()->success('data', $data, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
