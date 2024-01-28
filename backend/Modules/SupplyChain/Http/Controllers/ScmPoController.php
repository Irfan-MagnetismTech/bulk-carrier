<?php

namespace Modules\SupplyChain\Http\Controllers;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmPoItem;
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Http\Requests\ScmPoRequest;

class ScmPoController extends Controller
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
            $scmWarehouses = ScmPo::query()
                ->with('scmPoLines.scmPoItems.scmMaterial', 'scmPoTerms', 'scmVendor', 'scmWarehouse', 'scmPoItems.scmMaterial')
                ->globalSearch($request->all());

            return response()->success('Data list', $scmWarehouses, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmPoRequest $request)
    {
        $requestData = $request->except('ref_no');
        $requestData['ref_no'] = UniqueId::generate(ScmPo::class, 'PO');
        $requestData['created_by'] = auth()->id();

        try {
            DB::beginTransaction();
            $scmPo = ScmPo::create($requestData);

            $this->createScmPoLinesAndItems($request, $scmPo);
            $scmPo->scmPoTerms()->createMany($request->scmPoTerms);

            DB::commit();
            return response()->success('Data created successfully', $scmPo, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function show(ScmPo $purchaseOrder): JsonResponse
    {
        try {
            $purchaseOrder->load('scmPoLines.scmPoItems.scmMaterial', "scmPoLines.scmPr", 'scmPoTerms', 'scmVendor', 'scmWarehouse', 'scmPoItems', 'scmCs', 'scmPoLines.scmPoItems.scmCsMaterial.scmMaterial', 'scmPoLines.scmPoItems.scmPrLine.scmMaterial');

            $scmPoLines = $purchaseOrder->scmPoLines->map(function ($items) {
                $datas = $items;

                $adas = $items->scmPoItems->map(function ($item) {
                    if (isset($item['cs_composite_key'])) {
                        $max_quantity = $item->scmCsMaterial->quantity -  $item->scmCsMaterial->scmPoItems->sum('quantity') + $item->quantity;
                    } else {
                        $max_quantity =  $item->scmPrLine->quantity -  $item->scmPrLine->scmPoItems->sum('quantity') + $item->quantity;
                    }
                    return [
                        'scm_material_id' => $item['scm_material_id'],
                        'scmMaterial' => $item['scmMaterial'],
                        'unit' => $item['unit'],
                        'brand' => $item['brand'],
                        'model' => $item['model'],
                        'required_date' => $item['required_date'],
                        'quantity' => $item['quantity'],
                        'tolarence_level' => $item['tolarence_level'],
                        'rate' => $item['rate'],
                        'total_price' => $item['total_price'],
                        'net_rate' => $item['net_rate'],
                        'po_composite_key' => $item['po_composite_key'],
                        'pr_composite_key' => $item['pr_composite_key'],
                        'cs_composite_key' => $item['cs_composite_key'],
                        'max_quantity' => $max_quantity,
                        'pr_quantity' => $item['quantity'],
                    ];
                });
                //data_forget scmPoItems

                data_forget($items, 'scmPoItems');
                $datas['scmPoItems'] = $adas;

                // $data = [
                //     'scm_material_id' => $item->scmMaterial->id,
                //     'scmMaterial' => $item->scmMaterial,
                //     'unit' => $item->unit,
                //     'brand' => $item->brand,
                //     'model' => $item->model,
                //     'required_date' => $item->required_date,
                //     'quantity' => $item->quantity,
                //     'rate' => $item->rate ?? 0,
                //     'total_price' => $item->total_price,
                //     'net_rate' => $item->net_rate,
                //     'po_composite_key' => $item->po_composite_key,
                //     'pr_composite_key' => $item->pr_composite_key,
                //     'max_quantity' => $item->scmPrLine->quantity - $item->scmPrLine->scmPoLines->sum('quantity') + $item->quantity,
                // ];

                // return $data;

                return $datas;
            });

            // return response()->json($scmPoLines, 422);

            // data_forget($purchaseOrder, 'scmPoLines');

            // $purchaseOrder->scmPoLines = $scmPoLines;

            // data forget scmPolines.scmPoItems and data set
            // $purchaseOrder->scmPoLines->map(function ($item) {
            //     $item->scmPoItems->map(function ($item) {
            //
            //         return $item;
            //     });
            //     return $item;
            // });



            return response()->success('data', $purchaseOrder, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function update(ScmPoRequest $request, ScmPo $purchaseOrder): JsonResponse
    {
        $requestData = $request->except('ref_no');

        try {
            DB::beginTransaction();

            $purchaseOrder->update($requestData);
            $purchaseOrder->scmPoLines()->delete();
            $purchaseOrder->scmPoItems()->delete();
            $purchaseOrder->scmPoTerms()->delete();

            $this->createScmPoLinesAndItems($request, $purchaseOrder);

            $purchaseOrder->scmPoTerms()->createMany($request->scmPoTerms);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }

        return response()->success('Data updated successfully!',  $purchaseOrder, 202);
    }

    private function createScmPoLinesAndItems($request, $purchaseOrder)
    {
        foreach ($request->scmPoLines as $key => $values) {
            $scmPoLine = $purchaseOrder->scmPoLines()->create([
                'scm_po_id' => $purchaseOrder->id,
                'scm_pr_id' => $values['scm_pr_id'],
            ]);

            foreach ($values['scmPoItems'] as $index => $value) {
                $this->createScmPoItem($request, $scmPoLine, $purchaseOrder, $value, $index);
            }
        }
    }

    private function createScmPoItem($request, $scmPoLine, $purchaseOrder, $value, $index)
    {
        ScmPoItem::create([
            'scm_po_line_id' => $scmPoLine->id,
            'scm_po_id' => $purchaseOrder->id,
            'scm_material_id'   => $value['scm_material_id'],
            'unit' => $value['unit'],
            'brand' => $value['brand'],
            'model' => $value['model'],
            'required_date' => $value['required_date'],
            'quantity' => $value['quantity'],
            'rate' => $value['rate'],
            'total_price' => $value['total_price'],
            'cs_composite_key' => $value['cs_composite_key'] ?? null,
            'tolarence_level' => $value['tolarence_level'],
            'net_rate' => $value['total_price'] / $request['sub_total'] * $request['net_amount'] / $value['quantity'],
            'po_composite_key' => CompositeKey::generate($index, $purchaseOrder->id, 'po', $value['scm_material_id'], $scmPoLine->id),
            'pr_composite_key' => $value['pr_composite_key'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function destroy(ScmPo $purchaseOrder)
    {
        try {
            DB::beginTransaction();

            $purchaseOrder->scmPoLines()->delete();
            $purchaseOrder->scmPoItems()->delete();
            $purchaseOrder->scmPoTerms()->delete();
            $purchaseOrder->delete();

            DB::commit();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json($purchaseOrder->preventDeletionIfRelated(), 422);

            // return response()->json($e, 422);
            // if ($e->errorInfo[1] == 1451) {
            //     // Custom error response for foreign key constraint violation
            //     return response()->json(['error' => 'Cannot delete parent record because it has related child records.'], 422);
            // }
            // return response()->error($e->getMessage(), 500);
        }
    }

    public function searchWarehouse(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $scmPo = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms')
                ->whereBusinessUnit($request->business_unit)
                ->where('name', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('name')
                ->limit(10)
                ->get();
        } else {
            $scmPo = [];
        }

        return response()->success('Search result', $scmPo, 200);
    }

    /**
     * Adds the net rate to the request data and generates the po composite key
     *
     * @param mixed $request
     * @param int $po_id
     * @return mixed
     */
    public function addNetRateToRequestData($request, $po_id): mixed
    {
        $net_amount = $request['net_amount'];
        $sub_total = $request['sub_total'];
        $scmPoLines = $request['scmPoLines'];
        foreach ($scmPoLines as $key => $value) {
            $scmPoLines[$key]['net_rate'] = $value['total_price'] / $sub_total * $net_amount / $value['quantity'];
            $scmPoLines[$key]['po_composite_key'] = CompositeKey::generate($key, $po_id, 'po', $value['scm_material_id']);
        }
        $request['scmPoLines'] = $scmPoLines;

        return $request;
    }

    public function getPoOrPoCsWisePrData(Request $request): JsonResponse
    {
        try {
            if ($request->cs_id == null) {
                $scmPr = ScmPr::query()
                    ->with([
                        'scmWarehouse',
                        'scmPrLines.scmMaterial',
                    ])
                    ->where('id', $request->pr_id)
                    ->first();

                $data = [
                    'scmWarehouse' => $scmPr->scmWarehouse,
                    'scm_warehouse_id' => $scmPr->scm_warehouse_id,
                    'pr_no' => $scmPr->ref_no,
                    'scm_pr_id' => $scmPr->id,
                    'scmPr' => $scmPr,
                    'pr_date' => $scmPr->raised_date,
                    'business_unit' => $scmPr->business_unit,
                    'purchase_center' => $scmPr->purchase_center,
                    'scmPoLines' => $scmPr->scmPrLines->map(function ($item) {
                        return [
                            'scmMaterial' => $item->scmMaterial,
                            'scm_material_id' => $item->scmMaterial->id,
                            'unit' => $item->scmMaterial->unit,
                            'brand' => $item->brand,
                            'model' => $item->model,
                            'quantity' => $item->quantity,
                            'pr_composite_key' => $item->pr_composite_key,
                            'max_quantity' => $item->quantity - $item->scmPoLines->sum('quantity'),
                            'rate' => 0,
                            // 'total_price' => $item->total_price
                        ];
                    })
                ];
            } else {
                $scmCs = ScmCs::query()
                    ->with('scmWarehouse', 'scmPr')
                    ->find('id', $request->cs_id);

                $data = [
                    'scmWarehouse' => $scmCs->scmWarehouse,
                    'scm_warehouse_id' => $scmCs->scm_warehouse_id,
                    'pr_no' => $scmCs->scmPr->ref_no,
                    'cs_no' => $scmCs->ref_no,
                    'scm_pr_id' => $scmCs->scmPr->id,
                    'scmPr' => $scmCs->scmPr,
                    'pr_date' => $scmCs->scmPr->raised_date,
                    'business_unit' => $scmCs->scmPr->business_unit,
                    'purchase_center' => $scmCs->scmPr->purchase_center,
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
    public function getMaterialByPrId(): JsonResponse
    {
        if (!request()->has('cs_id')) {
            $prMaterials = ScmPrLine::query()
                ->with('scmMaterial')
                ->where('scm_pr_id', request()->pr_id)
                ->whereNot('status', 'Closed')
                ->get()
                ->map(function ($item) {
                    $data = $item->scmMaterial;
                    $data['brand'] = $item->brand;
                    $data['model'] = $item->model;
                    $data['unit'] = $item->scmMaterial->unit;
                    $data['pr_composite_key'] = $item->pr_composite_key;
                    $data['pr_quantity'] = $item->quantity;
                    $data['quantity'] = $item->quantity;
                    if (request()->po_id) {
                        $data['po_quantity'] = $item->scmPoItems->where('scm_po_id', request()->po_id)->where('pr_composite_key', $item->pr_composite_key)->first()->quantity;
                    } else {
                        $data['po_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmPoItems->sum('quantity') + $data['po_quantity'];
                    return $data;
                });
        } else {
            $prMaterials = ScmCsMaterial::query()
                ->where([
                    'scm_cs_id' => request()->cs_id,
                    'scm_pr_id' => request()->pr_id
                ])
                ->get()
                ->map(function ($item) {
                    $data = $item->scmMaterial;
                    $data['scm_material_id'] = $item->scmMaterial->id;
                    $data['unit'] = $item->scmMaterial->unit;
                    $data['brand'] = $item->brand;
                    $data['model'] = $item->model;
                    $data['quantity'] = $item->quantity;
                    $data['cs_composite_key'] = $item->cs_composite_key;
                    $data['pr_composite_key'] = $item->pr_composite_key;
                    $data['pr_quantity'] = $item->scmPrLine->quantity;
                    $data['quantity'] = $item->quantity;
                    if (request()->po_id) {
                        $data['po_quantity'] = $item->scmPoItems->where('scm_po_id', request()->po_id)->where('cs_composite_key', $item->cs_composite_key)->first()->quantity;
                    } else {
                        $data['po_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmPoItems->sum('quantity') + $data['po_quantity'];

                    return $data;
                });
        }


        return response()->success('data list', $prMaterials, 200);
    }

    /**
     * Search for a PO based on the given request parameters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPo(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $scmPo = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms', 'scmVendor')
                ->whereBusinessUnit($request->business_unit)
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } else {
            $scmPo = [];
        }

        return response()->success('Search result', $scmPo, 200);
    }

    public function searchPoForLc(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $scmPo = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms', 'scmVendor')
                ->where('purchase_center', 'foreign')
                ->orWhere('purchase_center', 'FOREIGN')
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } else {
            $scmPo = [];
        }

        return response()->success('Search result', $scmPo, 200);
    }

    public function getPoLineDatas()
    {
        if (!request()->has('cs_id')) {
            $scmPr = ScmPrLine::query()
                ->where('scm_pr_id', request()->pr_id)
                ->whereNot('status', 'Closed')
                ->get()
                ->map(function ($item) {
                    $data['scm_material_id'] = $item->scmMaterial->id;
                    $data['scmMaterial'] = $item->scmMaterial;
                    $data['unit'] = $item->scmMaterial->unit;
                    $data['brand'] = $item->brand;
                    $data['model'] = $item->model;
                    $data['pr_composite_key'] = $item->pr_composite_key;
                    $data['pr_quantity'] = $item->quantity;
                    $data['quantity'] = $item->quantity;

                    if (request()->po_id) {
                        $data['po_quantity'] = $item->scmPoItems->where('scm_po_id', request()->po_id)->where('pr_composite_key', $item->pr_composite_key)->first()->quantity;
                    } else {
                        $data['po_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmPoItems->sum('quantity') + $data['po_quantity'];
                    $data['po_quantity'] = $data['po_quantity'] ?? 0;

                    return $data;
                });
        } else {
            $scmPr = ScmCsMaterial::query()
                ->where([
                    'scm_cs_id' => request()->cs_id,
                    'scm_pr_id' => request()->pr_id
                ])
                ->get()
                ->map(function ($item) {
                    $data['scm_material_id'] = $item->scmMaterial->id;
                    $data['scmMaterial'] = $item->scmMaterial;
                    $data['unit'] = $item->scmMaterial->unit;
                    $data['brand'] = $item->brand;
                    $data['model'] = $item->model;
                    $data['quantity'] = $item->quantity;
                    $data['cs_composite_key'] = $item->cs_composite_key;
                    $data['pr_composite_key'] = $item->pr_composite_key;
                    $data['pr_quantity'] = $item->scmPrLine->quantity;
                    $data['quantity'] = $item->quantity;
                    if (request()->po_id) {
                        $data['po_quantity'] = $item->scmPoItems->where("scm_po_id", request()->po_id)->where('cs_composite_key', $item->cs_composite_key)->first()->quantity;
                    } else {
                        $data['po_quantity'] = 0;
                    }
                    $data['max_quantity'] = $item->quantity - $item->scmPoItems->sum('quantity') + $data['po_quantity'];

                    return $data;
                });
        }


        return response()->success('data', $scmPr, 200);
    }
}
