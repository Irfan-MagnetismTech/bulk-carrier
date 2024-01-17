<?php

namespace Modules\SupplyChain\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Imports\ScmMaterialsImport;
use App\Services\FileUploadService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\SupplyChain\Entities\ScmCs;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Entities\ScmMrr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmPrLine;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Entities\ScmCsMaterial;
use Modules\SupplyChain\Http\Requests\ScmPrRequest;
use Maatwebsite\Excel\Validators\ValidationException;

class ScmPrController extends Controller
{
    function __construct(private FileUploadService $fileUpload)
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
            $scm_prs = ScmPr::query()
                ->with(
                    'scmPrLines.scmMaterial',
                    'scmWarehouse',
                    'scmPos',
                    'scmMrrs',
                    'closedBy',
                    'createdBy',
                    'scmPrLines.closedBy',
                    'scmPrLines.createdBy'
                )
                ->globalSearch($request->all());

            return response()->success('Data list', $scm_prs, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmPrRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no', 'pr_composite_key');
        $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs');
        $requestData['attachment'] = $attachment;
        $requestData['created_by'] = auth()->user()->id;
        $requestData['ref_no'] = UniqueId::generate(ScmPr::class, 'PR');

        try {
            DB::beginTransaction();

            $purchase_requisition = ScmPr::create($requestData);
            if ($request->entry_type === '0') {
                $linesData = CompositeKey::generateArray($request->scmPrLines, $purchase_requisition->id, 'scm_material_id', 'pr');
                $purchase_requisition->scmPrLines()->createMany($linesData);
            } else {
                $import = new ScmMaterialsImport();
                Excel::import($import, $request->file('excel'));
                ob_end_clean();

                if ($import->invalid) {
                    return response()->json($import->invalid, 422);
                } else {
                    $linesData = CompositeKey::generateArray($import->uniqueRows, $purchase_requisition->id, 'scm_material_id', 'pr');
                    $purchase_requisition->scmPrLines()->createUpdateOrDelete($linesData);
                }
            }

            DB::commit();
            return response()->success('Data created succesfully', $purchase_requisition, 201);
        } catch (ValidationException $e) {
            DB::rollBack();

            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row();
                $failure->attribute();
                $failure->errors();
                $failure->values();
            }

            return response()->json($e->failures(), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 501);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmPr $purchase_requisition
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $purchaseRequisition = ScmPr::query()
            ->with('scmPrLines.scmMaterial', 'scmWarehouse', 'scmPrLines.scmStockLedgers', 'closedBy', 'createdBy', 'scmPrLines.closedBy', 'scmPrLines.createdBy')
            ->find($id);

        $prLines = $purchaseRequisition->scmPrLines->map(function ($scmPrLine) use ($purchaseRequisition) {

            $currentStock = $scmPrLine->scmStockLedgers->where('scm_warehouse_id', $purchaseRequisition->scm_warehouse_id)->sum('quantity');

            $lines = [
                'id' => $scmPrLine->id,
                'scm_material_id' => $scmPrLine->scmMaterial->id,
                'scmMaterial' => $scmPrLine->scmMaterial,
                'unit' => $scmPrLine->unit,
                'brand' => $scmPrLine->brand,
                'model' => $scmPrLine->model,
                'specification' => $scmPrLine->specification,
                'country_name' => $scmPrLine?->country_name,
                'drawing_no' => $scmPrLine->drawing_no,
                'part_no' => $scmPrLine->part_no,
                'rob' => $currentStock,
                'quantity' => $scmPrLine->quantity,
                'is_closed' => $scmPrLine->is_closed,
                'closed_by' => $scmPrLine->closedBy->name ?? null,
                'closed_at' => $scmPrLine->closed_at,
                'closing_remarks' => $scmPrLine->closing_remarks,
                'required_date' => $scmPrLine->required_date,
                'status' => $scmPrLine->status,
            ];
            return $lines;
        });

        $purchaseRequisition = [
            'pr_no' => $purchaseRequisition->ref_no,
            'ref_no' => $purchaseRequisition->ref_no,
            'business_unit' => $purchaseRequisition->business_unit,
            'scmWarehouse' => $purchaseRequisition->scmWarehouse,
            'scm_warehouse_id' => $purchaseRequisition->scm_warehouse_id,
            'acc_cost_center_id' => $purchaseRequisition->acc_cost_center_id,
            'raised_date' => $purchaseRequisition->raised_date,
            'is_critical' => $purchaseRequisition->is_critical,
            'attachment' => $purchaseRequisition->attachment,
            'remarks' => $purchaseRequisition->remarks,
            'purchase_center' => $purchaseRequisition->purchase_center,
            'approved_date' => $purchaseRequisition->approved_date,
            'remarks' => $purchaseRequisition->remarks,
            'is_closed' => $purchaseRequisition->is_closed,
            'closed_by' => $purchaseRequisition->closedBy->name ?? null,
            'closed_at' => $purchaseRequisition->closed_at,
            'closing_remarks' => $purchaseRequisition->closing_remarks,
            'status' => $purchaseRequisition->status,
            'created_by' => $purchaseRequisition->createdBy->name,
            'scmPrLines' => $prLines,
        ];

        try {
            return response()->success('Data updated sucessfully!', $purchaseRequisition, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ScmPr $purchase_requisition
     * @return JsonResponse
     */
    public function update(ScmPrRequest $request, ScmPr $purchase_requisition): JsonResponse
    {
        $requestData = $request->except('ref_no', 'pr_composite_key','created_by');

        $linesData = CompositeKey::generateArray($request->scmPrLines, $purchase_requisition->id, 'scm_material_id', 'pr');

        try {
            DB::beginTransaction();

            $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs', $purchase_requisition->attachment);
            $requestData['attachment'] = $attachment;

            $purchase_requisition->update($requestData);
            $purchase_requisition->scmPrLines()->createUpdateOrDelete($linesData);

            DB::commit();
            return response()->success('Data updated sucessfully!', $purchase_requisition, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmPr $purchaseRequisition
     * @return JsonResponse
     */
    public function destroy(ScmPr $purchaseRequisition): JsonResponse
    {
        try {
            $poCount = ScmPo::where('scm_pr_id', $purchaseRequisition->id)->count();
            $csCount = ScmCsMaterial::where('scm_pr_id', $purchaseRequisition->id)->count();
            $mrrCount = ScmMrr::where('scm_pr_id', $purchaseRequisition->id)->count();

            if ($poCount + $csCount + $mrrCount > 0) {
                $error = array(
                    "message" => "Data could not be deleted!",
                    "errors" => [
                        "id" => ["This data could not be deleted as it has references in other table!"]
                    ]
                );
                return response()->json($error, 422);
            }

            $purchaseRequisition->scmPrLines()->delete();
            $purchaseRequisition->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Search for opening stock.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPr(Request $request): JsonResponse
    {
        if (isset($request->searchParam)) {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->where('acc_cost_center_id', $request->cost_center_id);
                })
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        } else {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->where(function ($query) use ($request) {
                    $query->where('business_unit', $request->business_unit)
                        ->where('acc_cost_center_id', $request->cost_center_id);
                })
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        }

        return response()->success('Search result', $purchase_requisition, 200);
    }


    public function searchPurchaseRequisitions(Request $request): JsonResponse
    {
        $purchase_requisition = [];
        if (isset($request->searchParam)) {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->whereNot('status', 'Closed')
                ->where(function ($query) use ($request) {
                    $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
                        ->where('business_unit', $request->business_unit)
                        ->when($request->scm_warehouse_id, function ($query) use ($request) {
                            $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                            ->where('purchase_center', $request->purchase_center);
                        });
                })
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } elseif (isset($request->cs_id)) {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->whereNot('status', 'Closed')
                ->when($request->scm_warehouse_id, function ($query) use ($request) {
                    $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                    ->where('business_unit', $request->business_unit)
                    ->where('purchase_center', $request->purchase_center);
                })
                ->whereHas('scmCsMaterial', function ($query) use ($request) {
                    $query->where('scm_cs_id', $request->cs_id);
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } elseif(isset($request->scm_warehouse_id)) {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->whereNot('status', 'Closed')
                ->when($request->scm_warehouse_id, function ($query) use ($request) {
                    $query->where('scm_warehouse_id', $request->scm_warehouse_id)
                    ->where('business_unit', $request->business_unit)
                    ->where('purchase_center', $request->purchase_center);
                })
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        }

        return response()->success('Search result', $purchase_requisition, 200);
    }


    // public function searchMrr(Request $request): JsonResponse
    // {
    //     if($request->has('searchParam')) {
    //         $materialReceiptReport = ScmMrr::query()
    //         ->with('scmMrrLines.scmMaterial')
    //         ->where(function($query) use ($request) {
    //             $query->where('ref_no', 'like', '%' . $request->searchParam . '%')
    //             ->orWhere('business_unit', 'like', '%' . $request->business_unit . '%')
    //             ->orWhere('acc_cost_center_id', 'like', '%' . $request->cost_center_id . '%');
    //         })
    //         ->orderByDesc('ref_no')
    //         ->limit(10)
    //         ->get();

    //     }else{
    //         $materialReceiptReport = ScmMrr::query()
    //         ->with('scmMrrLines.scmMaterial')
    //         ->where(function($query) use ($request) {
    //             $query->where('business_unit', 'like', '%' . $request->business_unit . '%')
    //             ->orWhere('acc_cost_center_id', 'like', '%' . $request->cost_center_id . '%');
    //         })
    //         ->orderByDesc('ref_no')
    //         ->limit(10)
    //         ->get();
    //     }

    //     $materialReceiptReport = $materialReceiptReport->map(function($item) {
    //         $item->scmMaterials = $item->scmMrrLines->map(function($item1) {
    //                  return $item1->scmMaterial;
    //             });
    //          return $item;
    //         });

    //     return response()->success('Search Result', $materialReceiptReport, 200);
    // }


    public function getMaterialByPrIdForCs(Request $request): JsonResponse
    {
        $lineData = ScmPrLine::query()
            ->with('scmMaterial')
            ->where('scm_pr_id', $request->pr_id)
            ->whereNot('status', 'Closed')
            ->whereHas('scmPr', function ($query) {
                $query->whereIn('status', ['Pending', 'WIP']);
            })
            ->get()
            ->map(function ($item) {
                $data = $item->scmMaterial;
                $data['pr_composite_key'] = $item->pr_composite_key;
                $data['max_quantity'] = $item->quantity - $item->scmCsmaterials->sum('quantity');
                return $data;
            });

        return response()->success('Search result', $lineData, 200);
    }

    public function closePr(Request $request): JsonResponse
    {
        try {
            $pr = ScmPr::find($request->id);
            $pr->update([
                // 'is_closed' => 1,
                'status' => 'Closed',
                'closed_by' => auth()->user()->id,
                'closed_at' => now(),
                'closing_remarks' => $request->closing_remarks,
            ]);

            $pr->load('scmPrLines');
            foreach ($pr->scmPrLines as $prLine) {
                if ($prLine->status === 'Closed') {
                    continue;
                }
                $prLine->update([
                    // 'is_closed' => 1,
                    'status' => 'Closed',
                    'closed_by' => auth()->user()->id,
                    'closed_at' => now(),
                    'closing_remarks' => $request->closing_remarks,
                ]);
            }
            return response()->success('Data updated sucessfully!', $pr, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function closePrLine(Request $request): JsonResponse
    {
        try {
            $prLine = ScmPrLine::find($request->id);
            $prLine->update([
                // 'is_closed' => 1,
                'status' => 'Closed',
                'closed_by' => auth()->user()->id,
                'closed_at' => now(),
                'closing_remarks' => $request->closing_remarks,
            ]);

            $pr = ScmPr::find($request->parent_id);
            $pr->load('scmPrLines');

            $prLines = $pr->scmPrLines->count();
            // $sumIsClosed = $pr->scmPrLines->sum('is_closed');
            $sumIsClosed = $pr->scmPrLines->where('status', 'Closed')->count();

            if ($prLines === $sumIsClosed) {
                $pr->update([
                    // 'is_closed' => 1,
                    'status' => 'Closed',
                    'closed_by' => auth()->user()->id,
                    'closed_at' => now(),
                    'closing_remarks' => "All lines are closed",
                ]);
            }

            return response()->success('Data updated sucessfully!', [$prLines, $sumIsClosed], 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }
}
