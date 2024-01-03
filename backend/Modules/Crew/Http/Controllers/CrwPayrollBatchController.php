<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwPayrollBatch;
use Modules\Crew\Entities\CrwPayrollBatchHead;
use Modules\Crew\Entities\CrwPayrollBatchHeadLine;

class CrwPayrollBatchController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $CrwPayrollBatches = CrwPayrollBatch::with('opsVessel:id,name')->withCount('crwPayrollBatchLines as total_crew')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $CrwPayrollBatches, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {

        try {
            DB::transaction(function () use ($request)
            {
                $crwPayrollBatchData = $request->only('ops_vessel_id', 'year_month', 'compensation_type', 'process_date', 'net_payment', 'currency', 'working_days', 'total_crew','business_unit');
                $crwPayrollBatch     = CrwPayrollBatch::create($crwPayrollBatchData);
                $crwPayrollBatch->crwPayrollBatchLines()->createMany($request->crwPayrollBatchLines);

                foreach ($request->crwPayrollBatchHeads as $crwPayrollBatchHead)
                {                    
                    $batchHead = CrwPayrollBatchHead::create([
                        'crw_payroll_batch_id' => $crwPayrollBatch->id,
                        'head_type'            => $crwPayrollBatchHead['head_type'],
                        'head_name'            => $crwPayrollBatchHead['head_name'],
                        'amount'               => $crwPayrollBatchHead['amount'],
                    ]);

                    foreach ($crwPayrollBatchHead['crwPayrollBatchHeadLines'] as $crwBatchHead)
                    {
                        CrwPayrollBatchHeadLine::create([
                            'crw_payroll_batch_id'      => $crwPayrollBatch->id,
                            'crw_payroll_batch_head_id' => $batchHead->id,
                            'crw_crew_id'               => $crwBatchHead['crew_id'],
                            'head_type'                 => $crwBatchHead['head_type'],
                            'amount'                    => $crwBatchHead['amount'],
                        ]);
                    }
                }
            });

            return response()->success('Created Succesfully', [], 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param CrwPayrollBatch $crwPayrollBatch
     */
    public function show(CrwPayrollBatch $crwPayrollBatch)
    {
        try {
            $crwPayrollBatchData = $crwPayrollBatch
            ->load('opsVessel:id,name', 
            'crwPayrollBatchHeads', 
            // 'crwPayrollBatchHeads.crwPayrollBatchHeadLines.crwCrew:id,full_name,pre_mobile_no', 
            'crwPayrollBatchHeadLines.crwCrew:id,full_name,pre_mobile_no',
            'crwPayrollBatchLines.crwCrew:id,full_name,pre_mobile_no', 
            'crwPayrollBatchLines.crwSalaryStructure:id,net_amount', 
            'crwPayrollBatchLines.crwAttendanceLine:id,present_days,absent_days,payable_days'
        ); 


            return response()->success('Retrieved succesfully', $crwPayrollBatchData, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @param CrwPayrollBatch $crwPayrollBatch
     */
    public function update(Request $request, CrwPayrollBatch $crwPayrollBatch)
    {
        try {
            DB::transaction(function () use ($request, $crwPayrollBatch)
            {
                $crwPayrollBatchData = $request->only('ops_vessel_id', 'year_month', 'compensation_type', 'process_date', 'net_payment', 'currency', 'working_days', 'total_crew','business_unit');
                $crwPayrollBatch->update($crwPayrollBatchData);

                $crwPayrollBatch->crwPayrollBatchLines()->delete();
                $crwPayrollBatch->crwPayrollBatchHeads()->delete();
                $crwPayrollBatch->crwPayrollBatchHeadLines()->delete();
                
                $crwPayrollBatch->crwPayrollBatchLines()->createMany($request->crwPayrollBatchLines);

                foreach ($request->crwPayrollBatchHeads as $crwPayrollBatchHead)
                {                    
                    $batchHead = CrwPayrollBatchHead::create([
                        'crw_payroll_batch_id' => $crwPayrollBatch->id,
                        'head_type'            => $crwPayrollBatchHead['head_type'],
                        'head_name'            => $crwPayrollBatchHead['head_name'],
                        'amount'               => $crwPayrollBatchHead['amount'],
                    ]);

                    foreach ($crwPayrollBatchHead['crwPayrollBatchHeadLines'] as $crwBatchHead)
                    {
                        CrwPayrollBatchHeadLine::create([
                            'crw_payroll_batch_id'      => $crwPayrollBatch->id,
                            'crw_payroll_batch_head_id' => $batchHead->id,
                            'crw_crew_id'               => $crwBatchHead['crew_id'],
                            'head_type'                 => $crwBatchHead['head_type'],
                            'amount'                    => $crwBatchHead['amount'],
                        ]);
                    }
                }
            });
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param CrwPayrollBatch $crwPayrollBatch
     */
    public function destroy(CrwPayrollBatch $crwPayrollBatch)
    {
        try {
            $crwPayrollBatch->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
