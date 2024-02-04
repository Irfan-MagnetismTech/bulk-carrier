<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Services\FileUploadService;
use Modules\SupplyChain\Entities\ScmWrr;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Http\Requests\ScmWrrRequest;

class ScmWrrController extends Controller
{
    // use HasRoles;

    function __construct(private FileUploadService $fileUpload)
    {
        //     $this->middleware('permission:work-receipt-report-create|work-receipt-report-edit|work-receipt-report-show|work-receipt-report-delete', ['only' => ['index','show']]);
        //     $this->middleware('permission:work-receipt-report-create', ['only' => ['store']]);
        //     $this->middleware('permission:work-receipt-report-edit', ['only' => ['update']]);
        //     $this->middleware('permission:work-receipt-report-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): JsonResponse
    {
        try {
            $work_receipt_reports = ScmWrr::globalSearch(request()->all());

            return response()->success('Material Category list', $work_receipt_reports, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmWrrRequest $request): JsonResponse
    {
        $requestData = $request->all();
        try {            
            $work_receipt_report = ScmWrr::create($requestData);
            return response()->success('Data created succesfully', $work_receipt_report, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function show(ScmWrr $work_receipt_report): JsonResponse
    {
        try {
            return response()->success('data', $work_receipt_report, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmWrrRequest $request
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function update(ScmWrrRequest $request, ScmWrr $work_receipt_report): JsonResponse
    {
        $requestData = $request->all();
        try {            
            $work_receipt_report->update($requestData);
            return response()->success('Data updated sucessfully!', $work_receipt_report, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmWrr $material
     * @return JsonResponse
     */
    public function destroy(ScmWrr $work_receipt_report): JsonResponse
    {
        try {
            $work_receipt_report->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
