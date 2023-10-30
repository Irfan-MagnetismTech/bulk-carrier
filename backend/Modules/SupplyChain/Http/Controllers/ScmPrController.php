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
use Modules\SupplyChain\Entities\ScmMaterial;
use Modules\SupplyChain\Services\GenerateUniqueId;
use Modules\SupplyChain\Http\Requests\ScmPrRequest;
use Maatwebsite\Excel\Validators\ValidationException;

class ScmPrController extends Controller
{
    function __construct(private FileUploadService $fileUpload, private GenerateUniqueId $uniqueId)
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
            $scm_prs = ScmPr::query()
                ->with('scmPrLines')
                ->latest()
                ->when(request()->business_unit != "ALL", function ($q) {
                    $q->where('business_unit', request()->business_unit);
                })
                ->paginate(10);;

            return response()->success('Data list', $scm_prs, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmPrRequest $request)
    {
        $requestData = $request->except('ref_no');



        if (isset($request->attachment)) {
            $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs');
            $requestData['attachment'] = $attachment;
        }
        $requestData['created_by'] = auth()->user()->id;
        $requestData['ref_no'] = $this->uniqueId->generate(ScmPr::class, 'PR');

        try {
            // DB::beginTransaction();
            $purchase_requisition = ScmPr::create($requestData);
            if (request('enrty_type') == '0') {
                $purchase_requisition->scmPrLines()->createUpdateOrDelete($request->scmPrLines);
            } else {
                $import = new ScmMaterialsImport();
                Excel::import($import, $request->file('excel'));
                ob_end_clean();

                if ($import->invalid) {
                    return response()->json($import->invalid, 422);
                } else {
                    $purchase_requisition->scmPrLines()->createUpdateOrDelete($import->uniqueRows);
                }
            }
        } catch (ValidationException $e) {

            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row();
                $failure->attribute();
                $failure->errors();
                $failure->values();
            }

            // throw new Exception($failure->errors(), 422);
            return response()->json($e->failures(), 422);



            // DB::commit();

            // return response()->success('Data created succesfully', $purchase_requisition, 201);
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
        $purchase_requisition = ScmPr::query()
            ->with('scmPrLines.scmMaterial', 'scmWarehouse')
            ->with('scmPrLines', function ($item) {
                $item->withSum('scmStockLedgers as rob', 'quantity');
            })
            ->find($id);

        try {
            return response()->success('data', $purchase_requisition, 200);
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
    public function update(Request $request, ScmPr $purchase_requisition): JsonResponse
    {
        $requestData = $request->except('ref_no');

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
                ->where('ref_no', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('ref_no')
                ->limit(10)
                ->get();
        } else {
            $purchase_requisition = [];
        }

        return response()->success('Search result', $purchase_requisition, 200);
    }
}
