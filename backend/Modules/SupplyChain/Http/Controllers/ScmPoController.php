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
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Http\Requests\ScmPoRequest;

class ScmPoController extends Controller
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
    public function index(Request $request): JsonResponse
    {
        try {
            $scmWarehouses = ScmPo::query()
                ->with('scmPoLines', 'scmPoTerms', 'scmVendor', 'scmWarehouse', 'scmPr', 'scmMrrs')
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
    public function store(ScmPoRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no');
        $requestData['ref_no'] = $this->uniqueId->generate(ScmPo::class, 'PO');

        try {
            DB::beginTransaction();
            $scmPo = ScmPo::create($requestData);
            // $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmPoLines, $scmPo->id, 'scm_material_id', 'po');
            $data = $this->addNetRateToRequestData($request, $scmPo->id);
            $scmPo->scmPoLines()->createUpdateOrDelete($data->scmPoLines);
            $scmPo->scmPoTerms()->createUpdateOrDelete($request->scmPoTerms);
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
            $purchaseOrder->load('scmPoLines.scmMaterial', 'scmPoTerms', 'scmVendor', 'scmWarehouse', 'scmPr');
            // po line fillable  protected $fillable = [
            //     'scm_po_id', 'scm_material_id', 'unit', 'brand', 'model', 'required_date', 'quantity', 'rate', 'total_price', 'net_rate', 'po_composite_key', 'pr_composite_key',
            // ];

            $scmPoLines = $purchaseOrder->scmPoLines->map(function ($item) {
                $data = [
                    'scm_material_id' => $item->scmMaterial->id,
                    'scmMaterial' => $item->scmMaterial,
                    'unit' => $item->unit,
                    'brand' => $item->brand,
                    'model' => $item->model,
                    'required_date' => $item->required_date,
                    'quantity' => $item->quantity,
                    'rate' => $item->rate ?? 0,
                    'total_price' => $item->total_price,
                    'net_rate' => $item->net_rate,
                    'po_composite_key' => $item->po_composite_key,
                    'pr_composite_key' => $item->pr_composite_key,
                    'max_quantity' => $item->scmPrLine->quantity - $item->scmPrLine->scmPoLines->sum('quantity') + $item->quantity,
                ];

                return $data;
            });
            data_forget($purchaseOrder, 'scmPoLines');

            $purchaseOrder->scmPoLines = $scmPoLines;
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
            $addNetRateToRequestData = $this->addNetRateToRequestData($request, $purchaseOrder->id);
            $purchaseOrder->scmPoLines()->createUpdateOrDelete($addNetRateToRequestData->scmPoLines);
            $purchaseOrder->scmPoTerms()->createUpdateOrDelete($request->scmPoTerms);

            DB::commit();
            return response()->success('Data updated sucessfully!',  $purchaseOrder, 202);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmPo $purchaseOrder
     * @return JsonResponse
     */
    public function destroy(ScmPo $purchaseOrder)
    {

        // $purchaseOrder->delete();

        // $purchaseOrder->getAllMethods();
        // $expectedTypes = [
        //     'Illuminate\Database\Eloquent\Relations\HasOne',
        //     'Illuminate\Database\Eloquent\Relations\HasMany',
        //     'Illuminate\Database\Eloquent\Relations\BelongsTo',
        //     'Illuminate\Database\Eloquent\Relations\MorphMany',
        //     'Illuminate\Database\Eloquent\Relations\MorphOne',
        // ];
        // return response()->json($purchaseOrder->getAllMethods($expectedTypes), 422);

        // $allMethods = $purchaseOrder->getAllMethods();

        // $dataPair = [];
        // foreach ($allMethods as $method) {
        //     $dataPair[$method] = $purchaseOrder->{$method}()->count();
        //     if ($purchaseOrder->{$method}()->count() > 0) {
        //         $error = [
        //             "message" => "Data could not be deleted!",
        //             "errors" => [
        //                 "id" => ["This data could not be deleted as it has reference to other table"]
        //             ]
        //         ];
        // return response()->json($purchaseOrder->getAllMethods(), 422);
        //     }


        // }
        // return response()->json($dataPair, 422);





        try {
            // if ($purchaseOrder->scmLcRecords()->count() > 0 || $purchaseOrder->scmMrrs()->count() > 0) {
            //     $error = [
            //         "message" => "Data could not be deleted!",
            //         "errors" => [
            //             "id" => ["This data could not be deleted as it has reference to other table"]
            //         ]
            //     ];
            //     return response()->json($error, 422);
            // }
            // return response()->error('Data could not be deleted as it has reference to other table', 422);
            DB::beginTransaction();

            $purchaseOrder->scmPoTerms()->delete();
            $purchaseOrder->scmPoLines()->delete();
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
            $scmPoLines[$key]['po_composite_key'] = $this->compositeKey->generate($key, $po_id, 'po', $value['scm_material_id']);
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
        $prMaterials = ScmPrLine::query()
            ->with('scmMaterial', 'scmPoLines')
            ->where('scm_pr_id', request()->pr_id)
            ->get()
            ->map(function ($item) {
                $data = $item->scmMaterial;
                $data['brand'] = $item->brand;
                $data['model'] = $item->model;
                if (request()->po_id) {
                    $data['po_quantity'] = $item->scmPoLines->where('scm_po_id', request()->po_id)->where('pr_composite_key', $item->pr_composite_key)->first()->quantity;
                } else {
                    $data['po_quantity'] = 0;
                }
                $data['max_quantity'] = $item->quantity - $item->scmPoLines->sum('quantity') + $data['po_quantity'];
                $data['po_quantity'] = $data['po_quantity'] ?? 0;
                return $data;
            });
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
}
