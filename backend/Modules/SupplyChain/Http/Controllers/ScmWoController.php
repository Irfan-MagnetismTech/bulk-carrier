<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmWo;
use Modules\SupplyChain\Entities\ScmWr;
use Modules\SupplyChain\Entities\ScmWcs;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmWoItem;
use Modules\SupplyChain\Entities\ScmWrLine;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmWcsService;
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
            ->with('scmWoLines.scmWoItems.scmService','scmWoLines.scmWr', 'scmWoTerms', 'scmVendor', 'scmWarehouse', 'scmWoItems', 'scmWcs.scmWcsVendorServices.scmWr')
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
        // return response()->json( $request->all(), 422);
        $requestData['created_by'] = auth()->id();

        
        try {
            DB::beginTransaction();
            $scmWo = ScmWo::create($requestData);

            $this->createScmWoLinesAndItems($request, $scmWo);
            $scmWo->scmWoTerms()->createMany($request->scmWoTerms);

            DB::commit();
            return response()->success('Data created successfully', $scmWo, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

   /**
     * Show the specified resource.
     * @param ScmWo $workOrder
     * @return JsonResponse
     */
    public function show(ScmWo $workOrder): JsonResponse
    {
        try {
            $workOrder->load('scmWoLines.scmWoItems.scmService', "scmWoLines.scmWr", 'scmWoTerms', 'scmVendor', 'scmWarehouse', 'scmWoItems', 'scmWcs', 'scmWoLines.scmWoItems.scmWcsService.scmService', 'scmWoLines.scmWoItems.scmWrLine.scmService');

            $scmWoLines = $workOrder->scmWoLines->map(function ($items) {
                $datas = $items;

                $adas = $items->scmWoItems->map(function ($item) {
                    if (isset($item['wcs_composite_key'])) {
                        $max_quantity = $item->scmWcsService->quantity -  $item->scmWcsService->scmWoItems->sum('quantity') + $item->quantity;
                    } else {
                        $max_quantity =  $item->scmWrLine->quantity -  $item->scmWrLine->scmWoItems->sum('quantity') + $item->quantity;
                    }
                    return [
                        'scm_service_id' => $item['scm_service_id'],
                        'scmService' => $item['scmService'],
                        'required_date' => $item['required_date'],
                        'quantity' => $item['quantity'],
                        'rate' => $item['rate'],
                        'total_price' => $item['total_price'],
                        // 'description' => $item['description']?$item['description']:'',
                        'wo_composite_key' => $item['wo_composite_key'],
                        'wr_composite_key' => $item['wr_composite_key'],
                        'wcs_composite_key' => $item['wcs_composite_key'],
                        'max_quantity' => $max_quantity,
                        'wr_quantity' => $item['quantity'],
                    ];
                });
                //data_forget scmPoItems

                data_forget($items, 'scmWoItems');
                $datas['scmWoItems'] = $adas;

                return $datas;
            });

            // return response()->json($scmPoLines, 422);

            // data_forget($workOrder, 'scmPoLines');

            // $workOrder->scmPoLines = $scmPoLines;

            // data forget scmPolines.scmPoItems and data set
            // $workOrder->scmPoLines->map(function ($item) {
            //     $item->scmPoItems->map(function ($item) {
            //
            //         return $item;
            //     });
            //     return $item;
            // });


            return response()->success('data', $workOrder, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ScmWo $workOrder
     * @return JsonResponse
     */
    public function update(ScmWoRequest $request, ScmWo $workOrder): JsonResponse
    {
        $requestData = $request->except('ref_no');

        try {
            DB::beginTransaction();

            $workOrder->update($requestData);
            $workOrder->scmWoLines()->delete();
            $workOrder->scmWoItems()->delete();
            $workOrder->scmWoTerms()->delete();

            $this->createScmWoLinesAndItems($request, $workOrder);

            $workOrder->scmWoTerms()->createMany($request->scmWoTerms);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }

        return response()->success('Data updated successfully!',  $workOrder, 202);
    }


    private function createScmWoLinesAndItems($request, $workOrder)
    {
        foreach ($request->scmWoLines as $key => $values) {
            $scmWoLine = $workOrder->scmWoLines()->create([
                'scm_wo_id' => $workOrder->id,
                'scm_wr_id' => $values['scm_wr_id'],
            ]);

            foreach ($values['scmWoItems'] as $index => $value) {
                $this->createScmWoItem($request, $scmWoLine, $workOrder, $value, $index);
            }
        }
    }

    private function createScmWoItem($request, $scmWoLine, $workOrder, $value, $index)
    {
        ScmWoItem::create([
            'scm_wo_line_id' => $scmWoLine->id,
            'scm_wo_id' => $workOrder->id,
            'scm_service_id'   => $value['scm_service_id'],
            'required_date' => $value['required_date'],
            'quantity' => $value['quantity'],
            'rate' => $value['rate'],
            'total_price' => $value['total_price'],
            // 'description' => $value['description'],
            'wcs_composite_key' => $value['wcs_composite_key'] ?? null,
            'wo_composite_key' => CompositeKey::generate($index, $workOrder->id, 'wo', $value['scm_service_id'], $scmWoLine->id),
            'wr_composite_key' => $value['wr_composite_key'],
        ]);
    }

        /**
     * Remove the specified resource from storage.
     * @param ScmWo $workOrder
     * @return JsonResponse
     */
    public function destroy(ScmWo $workOrder)
    {
        try {
            DB::beginTransaction();

            $workOrder->scmWoLines()->delete();
            $workOrder->scmWoItems()->delete();
            $workOrder->scmWoTerms()->delete();
            $workOrder->delete();

            DB::commit();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json($workOrder->preventDeletionIfRelated(), 422);
        }
    }


    public function getWoOrWoWcsWiseWrData(Request $request): JsonResponse
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
                    'wr_date' => $scmWr->raised_date,
                    'business_unit' => $scmWr->business_unit,
                    'purchase_center' => $scmWr->purchase_center,
                    'scmWoLines' => $scmWr->scmWrLines->map(function ($item) {
                        return [
                            'scmService' => $item->scmService,
                            'scm_service_id' => $item->scmService->id,
                            'quantity' => $item->quantity,
                            'wr_composite_key' => $item->wr_composite_key,
                            'max_quantity' => $item->quantity - $item->scmWoLines->sum('quantity'),
                            // 'total_amount' => $item->total_amount
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


        /**
     * Retrieves the materials associated with a given purchase requisition ID.
     *
     * @return JsonResponse
     */
    public function getServiceByWrId(): JsonResponse
    {
        // return response()->json(request()->all(),422);
        $wrServices = ScmWrLine::query()
            ->with('scmService','scmWoItems')
            ->where('scm_wr_id', request()->scm_wr_id)
            ->whereNot('status', 'Closed')
            ->get()
            ->map(function ($item) {
                $data = $item->scmService;
                if (request()->scm_wo_id) {
                    $data['wo_quantity'] = $item->scmWoItems->where('scm_wo_id', request()->scm_wo_id)->where('wr_composite_key', $item->wr_composite_key)->first()->quantity;
                } else {
                    $data['wo_quantity'] = 0;
                }
                $data['max_quantity'] = $item->quantity - $item->scmWoItems->sum('quantity') + $data['wo_quantity'];
                $data['wo_quantity'] = $data['wo_quantity'] ?? 0;
                return $data;
            });
        return response()->success('data list', $wrServices, 200);
    }
    
    /**
     * Search for a WO based on the given request parameters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchWo(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $scmWo = ScmWo::query()
                ->with('scmWoLines', 'scmWoTerms', 'scmVendor')
                ->whereBusinessUnit($request->business_unit)
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } else {
            $scmWo = [];
        }

        return response()->success('Search result', $scmWo, 200);
    }


    public function getWoLineDatas()
    {
        if (!request()->has('scm_wcs_id')) {
            $scmWr = ScmWrLine::query()
                ->where('scm_wr_id', request()->scm_wr_id)
                ->whereNot('status', 'Closed')
                ->get()
                ->map(function ($item) {
                    $data['scm_service_id'] = $item->scmService->id;
                    $data['scmService'] = $item->scmService;
                    $data['wr_composite_key'] = $item->wr_composite_key;
                    $data['wr_quantity'] = $item->quantity;
                    $data['quantity'] = $item->quantity;

                    if (request()->scm_wo_id) {
                        $data['wo_quantity'] = $item->scmWoItems->where('scm_wo_id', request()->scm_wo_id)->where('wr_composite_key', $item->wr_composite_key)->first()->quantity;
                    } else {
                        $data['wo_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmWoItems->sum('quantity') + $data['wo_quantity'];
                    $data['wo_quantity'] = $data['wo_quantity'] ?? 0;

                    return $data;
                });
        } else {
            $scmWr = ScmWcsService::query()
                ->where([
                    'scm_wcs_id' => request()->scm_wcs_id,
                    'scm_wr_id' => request()->scm_wr_id
                ])
                ->get()
                ->map(function ($item) {
                    $data['scm_service_id'] = $item->scmService->id;
                    $data['scmService'] = $item->scmService;
                    $data['quantity'] = $item->quantity;
                    $data['wcs_composite_key'] = $item->wcs_composite_key;
                    $data['wr_composite_key'] = $item->wr_composite_key;
                    $data['wr_quantity'] = $item->scmWrLine->quantity;
                    $data['quantity'] = $item->quantity;
                    if (request()->scm_wo_id) {
                        $data['wo_quantity'] = $item->scmWoItems->where('wcs_composite_key', $item->wcs_composite_key)->sum('quantity');
                    } else {
                        $data['wo_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmWoItems->sum('quantity') + $data['wo_quantity'];

                    return $data;
                });
        }


        return response()->success('data', $scmWr, 200);
    }
}
