<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Modules\SupplyChain\Entities\ScmPr;
use Modules\SupplyChain\Services\GenerateUniqueId;
use Modules\SupplyChain\Http\Requests\ScmPrRequest;

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
    public function store(ScmPrRequest $request): JsonResponse
    {
        $requestData = $request->except('ref_no');

        try {
            DB::beginTransaction();

            if (isset($request->attachment)) {
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/prs');
                $requestData['attachment'] = $attachment;
            }
            $requestData['created_by'] = auth()->user()->id;
            $requestData['ref_no'] = $this->uniqueId(ScmPr::class, 'PR');

            $scm_opening_stock = ScmPr::create($requestData);
            $scm_opening_stock->scmPrLines()->createMany($request->scmPrLines);

            DB::commit();

            return response()->success('Data created succesfully', $scm_opening_stock, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmPr $purchase_requisition
     * @return JsonResponse
     */
    public function show(ScmPr $purchase_requisition): JsonResponse
    {
        try {
            return response()->success('data', $purchase_requisition->load('scmPrLines.scmMaterial', 'scmWarehouse'), 200);
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

    public function searchOpeningStock(Request $request): JsonResponse
    {
        if ($request->business_unit != 'ALL') {
            $purchase_requisition = ScmPr::query()
                ->with('scmPrLines')
                ->whereBusinessUnit($request->business_unit)
                ->where('name', 'LIKE', "%$request->searchParam%")
                ->orderByDesc('name')
                ->limit(10)
                ->get();
        } else {
            $purchase_requisition = [];
        }

        return response()->success('Search result', $purchase_requisition, 200);
    }
}
