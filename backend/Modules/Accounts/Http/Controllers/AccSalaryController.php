<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccSalary;
use Modules\Accounts\Http\Requests\AccSalaryRequest;

class AccSalaryController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $accSalaries = AccSalary::with('costCenter')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $accSalaries, 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisitionRequest $request
     */
    public function store(AccSalaryRequest $request)
    {
        try {
            $accAdvanceAdjustmentData = $request->only('acc_cost_center_id','year_month','total_salary','remarks','business_unit');

            DB::transaction(function () use ($request, $accAdvanceAdjustmentData)
            {
                $accSalary = AccSalary::create($accAdvanceAdjustmentData);
                $accSalary->accSalaryLines()->createMany($request->accSalaryLines);
            });

            return response()->success('Created Successfully', [], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccSalary $accSalary
     */
    public function show(AccSalary $accSalary)
    {
        try {
            return response()->success('Retrieved Succesfully', $accSalary->load('accSalaryLines', 'costCenter'), 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccCashRequisitionRequest $request
     * @param AccSalary $accSalary
     */
    public function update(AccSalaryRequest $request, AccSalary $accSalary)
    {
        try {
            $accAdvanceAdjustmentData = $request->only('acc_cost_center_id','year_month','total_salary','remarks','business_unit');

            DB::transaction(function () use ($request, $accAdvanceAdjustmentData, $accSalary)
            {
                $accSalary->update($accAdvanceAdjustmentData);
                $accSalary->accSalaryLines()->delete();
                $accSalary->accSalaryLines()->createMany($request->accSalaryLines);
            });

            return response()->success('Updated Successfully', [], 202);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param AccSalary $accSalary
     */
    public function destroy(AccSalary $accSalary)
    {
        try {
            $accSalary->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}