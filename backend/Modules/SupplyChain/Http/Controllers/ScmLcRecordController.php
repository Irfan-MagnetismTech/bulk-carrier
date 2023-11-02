<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmLcRecord;
use Modules\SupplyChain\Http\Requests\ScmLcRecordRequest;

class ScmLcRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scmLcRecords = ScmLcRecord::with('scmLcRecordLines')->latest()->paginate(10);

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
        try {
            DB::beginTransaction();
            $scmLcRecord = ScmLcRecord::create($request->all());
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
            return response()->success('data', $lcRecord->load('scmLcRecordLines'), 200);
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
        try {
            $lcRecord->update($request->all());

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

    public function searchLcRecord(Request $request): JsonResponse
    {
        $lcRecord = ScmLcRecord::query()
            ->with('scmLcRecordLines')
            ->where('lc_no', 'like', "%$request->searchParam%")
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $lcRecord, 200);
    }
}
