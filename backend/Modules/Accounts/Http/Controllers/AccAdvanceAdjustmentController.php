<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccAdvanceAdjustment;
use Modules\Accounts\Http\Requests\AccAdvanceAdjustmentRequest;
use Modules\Accounts\Http\Requests\AccCashRequisitionRequest;
use App\Services\FileUploadService;

class AccAdvanceAdjustmentController extends Controller
{
    public function __construct(private FileUploadService $fileUpload)
    {

    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $accAdvanceAdjustments = AccAdvanceAdjustment::with('accAdvanceAdjustmentLines', 'costCenter', 'accCashRequisition')
                ->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $accAdvanceAdjustments, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisitionRequest $request
     */
    public function store(AccAdvanceAdjustmentRequest $request)
    {
        try {
            $accAdvanceAdjustmentData = $request->only('acc_cost_center_id', 'acc_cash_requisition_id', 'adjustment_date', 'adjustment_amount', 'cash_amount', 'business_unit');

            $accAdvanceAdjustmentLines = $this->fileUpload->handleMultipleFiles('acc/advance-adjustment', $request->accAdvanceAdjustmentLines, $request->attachment);

            DB::transaction(function () use ($accAdvanceAdjustmentData, $accAdvanceAdjustmentLines)
            {
                $accAdvanceAdjustment = AccAdvanceAdjustment::create($accAdvanceAdjustmentData);
                $accAdvanceAdjustment->accAdvanceAdjustmentLines()->createMany($accAdvanceAdjustmentLines);
            });

            return response()->success('Created Successfully', [], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccAdvanceAdjustment $accAdvanceAdjustment
     */
    public function show(AccAdvanceAdjustment $accAdvanceAdjustment)
    {
        try {
            return response()->success('Retrieved Successfully', $accAdvanceAdjustment->load('accAdvanceAdjustmentLines', 'costCenter', 'accCashRequisition'), 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisitionRequest $request
     * @param AccAdvanceAdjustment $accAdvanceAdjustment
     */
    public function update(AccAdvanceAdjustmentRequest $request, AccAdvanceAdjustment $accAdvanceAdjustment)
    {
        try {
            $accAdvanceAdjustmentData = $request->only('acc_cost_center_id', 'acc_cash_requisition_id', 'adjustment_date', 'adjustment_amount', 'cash_amount', 'business_unit');

            $accAdvanceAdjustmentLines = $this->fileUpload->handleMultipleFiles('acc/advance-adjustment', $request->accAdvanceAdjustmentLines, $request->attachment, $accAdvanceAdjustment->accAdvanceAdjustmentLines);

            DB::transaction(function () use ($accAdvanceAdjustmentData, $accAdvanceAdjustment, $accAdvanceAdjustmentLines)
            {
                $accAdvanceAdjustment->update($accAdvanceAdjustmentData);
                $accAdvanceAdjustment->accAdvanceAdjustmentLines()->delete();
                $accAdvanceAdjustment->accAdvanceAdjustmentLines()->createMany($accAdvanceAdjustmentLines);
            });

            return response()->success('Updated Successfully', [], 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccAdvanceAdjustment $accAdvanceAdjustment
     */
    public function destroy(AccAdvanceAdjustment $accAdvanceAdjustment)
    {
        try {
            $accAdvanceAdjustment->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
