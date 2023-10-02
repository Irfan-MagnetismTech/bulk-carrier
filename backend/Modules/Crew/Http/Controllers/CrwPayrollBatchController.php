<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwPayrollBatch;

class CrwPayrollBatchController extends Controller
{
    public function index()
    {
        try {
            $CrwPayrollBatches = CrwPayrollBatch::get();

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
                $crwPayrollBatchData = $request->only('ops_vessel_id', 'month_no', 'year', 'compensation_type', 'start_date', 'end_date', 'process_date', 'net_payment', 'currency', 'working_days', 'business_unit');
                $crwPayrollBatch     = CrwPayrollBatch::create($crwPayrollBatchData);
                $crwPayrollBatch->crwPayrollBatchLines()->createMany($request->crwPayrollBatchLines);
                $crwPayrollBatch->crwPayrollBatchHeads()->createMany($request->crwPayrollBatchHeads);
                $crwPayrollBatch->crwPayrollBatchHeadLines()->createMany($request->crwPayrollBatchHeadLines);

                return response()->success('Created Succesfully', $crwPayrollBatch, 201);
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
    public function show(CrwPayrollBatch $crwPayrollBatch)
    {
        try {
            return response()->success('Retrieved succesfully', $crwPayrollBatch->load('crwPayrollBatchLines', 'crwPayrollBatchHeads', 'crwPayrollBatchHeadLines'), 200);
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
                $crwPayrollBatchData = $request->only('ops_vessel_id', 'month_no', 'year', 'compensation_type', 'start_date', 'end_date', 'process_date', 'net_payment', 'currency', 'working_days', 'business_unit');
                $crwPayrollBatch->update($crwPayrollBatchData);
                $crwPayrollBatch->crwPayrollBatchLines()->delete();
                $crwPayrollBatch->crwPayrollBatchHeads()->delete();
                $crwPayrollBatch->crwPayrollBatchHeadLines()->delete();

                $crwPayrollBatch->crwPayrollBatchLines()->createMany($request->crwPayrollBatchLines);
                $crwPayrollBatch->crwPayrollBatchHeads()->createMany($request->crwPayrollBatchHeads);
                $crwPayrollBatch->crwPayrollBatchHeadLines()->createMany($request->crwPayrollBatchHeadLines);

                return response()->success('Updated succesfully', $crwPayrollBatch, 202);
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
