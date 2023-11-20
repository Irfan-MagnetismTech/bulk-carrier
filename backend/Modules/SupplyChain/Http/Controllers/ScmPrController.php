<?php

namespace Modules\SupplyChain\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Imports\ScmMaterialsImport;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Http\Requests\ScmPrRequest;
use Maatwebsite\Excel\Validators\ValidationException;

class ScmPrController extends Controller
{
    function __construct(private FileUploadService $fileUpload, private UniqueId $uniqueId, private CompositeKey $compositeKey)
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
                ->with('scmPrLines', 'scmWarehouse')
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
        $requestData = $request->except('ref_no');

        if (!empty($request->attachment)) {
            $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs');
            $requestData['attachment'] = $attachment;
        }
        $requestData['created_by'] = auth()->user()->id;
        $requestData['ref_no'] = $this->uniqueId->generate(ScmPr::class, 'PR');

        try {
            DB::beginTransaction();

            $purchase_requisition = ScmPr::create($requestData);
            if ($request->entry_type === '0') {
                $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmPrLines, $purchase_requisition->id, 'scm_material_id', 'pr');
                $purchase_requisition->scmPrLines()->createMany($linesData);
            } else {
                $import = new ScmMaterialsImport();
                Excel::import($import, $request->file('excel'));
                ob_end_clean();

                if ($import->invalid) {
                    return response()->json($import->invalid, 422);
                } else {
                    $linesData = $this->compositeKey->generateArrayWithCompositeKey($import->uniqueRows, $purchase_requisition->id, 'scm_material_id', 'pr');
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
            ->with('scmPrLines.scmMaterial', 'scmWarehouse', 'scmPrLines.scmStockLedgers')
            ->find($id);

        $prLines = $purchaseRequisition->scmPrLines->map(function ($scmPrLine) use ($purchaseRequisition) {

            $currentStock = $scmPrLine->scmStockLedgers->where('scm_warehouse_id', $purchaseRequisition->scm_warehouse_id)->sum('quantity');

            $lines = [
                'scm_material_id' => $scmPrLine->scmMaterial->id,
                'scmMaterial' => $scmPrLine->scmMaterial,
                'unit' => $scmPrLine->unit,
                'brand' => $scmPrLine->brand,
                'model' => $scmPrLine->model,
                'specification' => $scmPrLine->specification,
                'origin' => $scmPrLine?->country?->name,
                'drawing_no' => $scmPrLine->drawing_no,
                'part_no' => $scmPrLine->part_no,
                'rob' => $currentStock,
                'quantity' => $scmPrLine->quantity,
                'required_date' => $scmPrLine->required_date
            ];
            return $lines;
        });

        $purchaseRequisition = [
            'pr_no' => $purchaseRequisition->ref_no,
            'ref_no' => $purchaseRequisition->ref_no,
            'business_unit' => $purchaseRequisition->business_unit,
            'scmWarehouse' => $purchaseRequisition->scmWarehouse->id,
            'scm_warehouse_id' => $purchaseRequisition->scm_warehouse_id,
            'raised_date' => $purchaseRequisition->raised_date,
            'is_critical' => $purchaseRequisition->is_critical,
            'attachment' => $purchaseRequisition->attachment,
            'remarks' => $purchaseRequisition->remarks,
            'purchase_center' => $purchaseRequisition->purchase_center,
            'approved_date' => $purchaseRequisition->approved_date,
            'remarks' => $purchaseRequisition->remarks,
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
        $requestData = $request->except('ref_no', 'pr_composite_key');

        try {
            DB::beginTransaction();

            if (isset($request->attachment)) {
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs', $purchase_requisition->attachment);
                $requestData['attachment'] = $attachment;
            }
            $purchase_requisition->update($requestData);
            $purchase_requisition->scmPrLines()->createUpdateOrDelete($request->scmPrLines);

            DB::commit();
            return response()->success('Data updated sucessfully!', $purchase_requisition, 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmPr $purchase_requisition
     * @return JsonResponse
     */
    public function destroy(ScmPr $purchase_requisition): JsonResponse
    {
        try {
            $purchase_requisition->scmPrLines()->delete();
            $purchase_requisition->delete();

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
    public function searchOpeningStock(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->whereBusinessUnit($request->business_unit)
                // ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                // ->limit(10)
                ->get();
        } else {
            $purchase_requisition = [];
        }

        return response()->success('Search result', $purchase_requisition, 200);
    }
}
