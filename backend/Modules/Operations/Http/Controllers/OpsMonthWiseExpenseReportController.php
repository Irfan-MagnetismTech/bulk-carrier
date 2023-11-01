<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsMonthWiseExpenseReport;
use Modules\Operations\Http\Requests\OpsMonthWiseExpenseReportRequest;

class OpsMonthWiseExpenseReportController extends Controller
{
    // use HasRoles;  
    
    // function __construct()
    // {
    //     $this->middleware('permission:month-wise-expense-report-create|month-wise-expense-report-edit|month-wise-expense-report-show|month-wise-expense-report-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:month-wise-expense-report-create', ['only' => ['store']]);
    //     $this->middleware('permission:month-wise-expense-report-edit', ['only' => ['update']]);
    //     $this->middleware('permission:month-wise-expense-report-delete', ['only' => ['destroy']]);
    // }

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $month_wise_expense_reports = OpsMonthWiseExpenseReport::latest()->paginate(15);

            return response()->success('Successfully retrieved month wise expense reports.', $month_wise_expense_reports, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    
    /**
     * Store a newly created resource in storage.
     * @param OpsMonthWiseExpenseReportRequest $request
     * @return JsonResponse
     */
    public function store(OpsMonthWiseExpenseReportRequest $request): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $month_wise_expense_reports = OpsMonthWiseExpenseReport::create($request->all());
            DB::commit();   
            return response()->success('Month wise expense report added Successfully.', $month_wise_expense_reports, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified port.
     *
     * @param  OpsMonthWiseExpenseReport  $month_wise_expense_report
     * @return JsonResponse
     */
    public function show(OpsMonthWiseExpenseReport $month_wise_expense_report): JsonResponse
    {
        try {            
            return response()->success('Successfully retrieved month wise expense report.', $month_wise_expense_report, 200);
        } catch (QueryException $e){
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OpsMonthWiseExpenseReportRequest  $request
     * @param  OpsMonthWiseExpenseReport  $month_wise_expense_report
     * @return JsonResponse
     */
    public function update(OpsMonthWiseExpenseReportRequest $request, OpsMonthWiseExpenseReport $month_wise_expense_report): JsonResponse
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $month_wise_expense_report->update($request->all());
            DB::commit();               
            return response()->success('Month wise expense report updated Successfully.', $month_wise_expense_report, 202);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OpsMonthWiseExpenseReport  $month_wise_expense_report
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpsMonthWiseExpenseReport $month_wise_expense_report): JsonResponse
    {
        try {
            $month_wise_expense_report->delete($month_wise_expense_report);

            return response()->json([
                'value' => '',
                'message' => 'Month wise expense report deleted Successfully.'
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);        
        }
    }


}
