<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Http\Requests\ScmLcRecordRequest;
use App\Services\FileUploadService;

class ScmLcRecordController extends Controller
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
    public function index(): JsonResponse
    {
        try {
            $scmLcRecords = ScmLcRecord::query()
                ->with('scmLcRecordLines', 'scmLcRecordStatuses', 'scmVendor', 'scmWarehouse', 'scmPo')
                ->globalSearch(request()->all());

            return response()->success('Data list', $scmLcRecords, 200);
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmLcRecordRequest $request): JsonResponse
    {
        $requestData = $request->all();
        try {
            DB::beginTransaction();
            if (!empty($request->attachment)) {
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/lcRecords');
                $requestData['attachment'] = $attachment;
            }
            $scmLcRecord = ScmLcRecord::create($requestData);
            $scmLcRecord->scmLcRecordLines()->createUpdateOrDelete($request->scmLcRecordLines);
            DB::commit();

            return response()->success('Data created succesfully', $scmLcRecord, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmLcRecord $lcRecord
     * @return JsonResponse
     */
    public function show(ScmLcRecord $lcRecord): JsonResponse
    {
        try {
            $lcRecord->load('scmLcRecordLines', 'scmLcRecordStatuses', 'scmVendor', 'scmWarehouse', 'scmPo.scmPoItems.scmMaterial');
            $lcRecord->scmPo->scmPoItems->map(function ($item) {
                return [
                    'scmMaterial' => $item['scmMaterial'],
                    'scm_po_id' => $item['scm_po_id'],
                    'scm_material_id' => $item['scm_material_id'],
                    'unit' => $item['unit'],
                    'brand' => $item['brand'],
                    'model' => $item['model'],
                    'quantity' => $item['quantity'],
                    'status' => $item['status'],
                ];
            })->unique('scm_material_id')->values()->all();
            return response()->success('data', $lcRecord , 200);
        } catch (\Exception $e) {


            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmLcRecordRequest $request
     * @param ScmLcRecord $lcRecord
     * @return JsonResponse
     */
    public function update(ScmLcRecordRequest $request, ScmLcRecord $lcRecord): JsonResponse
    {
        $requestData = $request->all();
        try {
            if (!empty($request->attachment)) {
                $attachment = $this->fileUpload->handleFile($request->attachment, 'scm/lcRecords', $lcRecord->attachment);
                $requestData['attachment'] = $attachment;
            }
            $lcRecord->update($requestData);

            $lcRecord->scmLcRecordLines()->createUpdateOrDelete($request->scmLcRecordLines);

            return response()->success('Data updated sucessfully!', $lcRecord, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmLcRecord $lcRecord
     * @return JsonResponse
     */
    public function destroy(ScmLcRecord $lcRecord): JsonResponse
    {
        try {
            $lcRecord->scmLcRecordLines()->delete();
            $lcRecord->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchLcRecord(): JsonResponse
    {
        $lcRecord = ScmLcRecord::query()
            ->with('scmLcRecordLines')
            ->whereBusinessUnitAndScmPoId(request()->business_unit, request()->scm_po_id)
            // ->where('lc_no', 'like', "%$request->searchParam%")
            ->orderByDesc('lc_no')
            // ->limit(10)
            ->get();

        return response()->success('Search result', $lcRecord, 200);
    }
}
