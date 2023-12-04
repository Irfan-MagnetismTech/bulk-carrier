<?php

namespace Modules\SupplyChain\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Imports\ScmMaterialsImport;
use App\Services\FileUploadService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Services\UniqueId;
use Modules\SupplyChain\Services\CompositeKey;
use Modules\SupplyChain\Http\Requests\ScmPrRequest;
use Maatwebsite\Excel\Validators\ValidationException;
use Modules\SupplyChain\Entities\ScmPo;
use Modules\SupplyChain\Entities\ScmPrLine;

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
        $requestData = $request->except('ref_no', 'pr_composite_key');
        $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs');
        $requestData['attachment'] = $attachment;
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
                'country_name' => $scmPrLine?->country_name,
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
            'scmWarehouse' => $purchaseRequisition->scmWarehouse,
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

        $linesData = $this->compositeKey->generateArrayWithCompositeKey($request->scmPrLines, $purchase_requisition->id, 'scm_material_id', 'pr');

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
     * @param ScmPr $purchase_requisition
     * @return JsonResponse
     */
    public function destroy(ScmPr $purchase_requisition): JsonResponse
    {
        try {
            $poLines = ScmPo::where('scm_pr_id', $purchase_requisition->id)->count();
            if ($poLines > 0) {
                $error = array(
                    "message" => "Data could not be deleted!",
                    "errors" => [
                        "id" => ["This data could not be deleted as it has reference to other table"]
                    ]
                );
                return response()->json($error, 422);
            }

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

    // getPrWiseCsData

    // const materialCs = ref({
    //     ref_no: '',
    //     date: '',
    //     expire_date: '',
    //     priority: '',
    //     scmWarehouse: '',
    //     scm_warehouse_id: '',
    //     scm_warehouse_name: '',
    //     acc_cost_center_id: '',
    //     scmPr: '',
    //     scm_pr_id: '',
    //     pr_no: '',
    //     special_instruction: '',
    //     purchase_center: '',
    //     business_unit: '',
    //     required_days: '',
    // });


    public function getPrWiseCsData(Request $request): JsonResponse
    {
        if(request()->filled('pr_id')){
        $pr_data = ScmPr::query()
            ->with('scmPrLines')
            ->find($request->pr_id);

            $data = [
                "scmWarehouse" => $pr_data->scmWarehouse,
                "scm_warehouse_id" => $pr_data->scm_warehouse_id,
                "scm_warehouse_name" => $pr_data->scmWarehouse->name,
                "acc_cost_center_id" => $pr_data->acc_cost_center_id,
                "scmPr" => $pr_data,
                "scm_pr_id" => $pr_data->id,
                "pr_no" => $pr_data->ref_no,
                "purchase_center" => $pr_data->purchase_center,
                "business_unit" => $pr_data->business_unit,
            ];
         }else{
            $data = [
                "scmWarehouse" => null,
                "scm_warehouse_id" => null,
                "scm_warehouse_name" => null,
                "acc_cost_center_id" => null,
                "scmPr" => null,
                "scm_pr_id" => null,
                "pr_no" => null,
                "purchase_center" => null,
                "business_unit" => null,
            ];
         }

        try {
            return response()->success
            ('Data updated sucessfully!', $data, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
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
}
