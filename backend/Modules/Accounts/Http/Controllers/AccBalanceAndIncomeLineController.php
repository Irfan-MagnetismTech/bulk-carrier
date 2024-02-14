<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;
use Modules\Accounts\Http\Requests\AccBalanceAndIncomeLineRequest;

class AccBalanceAndIncomeLineController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:acc-balance-income-line-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:acc-balance-income-line-create', ['only' => ['store']]);
        $this->middleware('permission:acc-balance-income-line-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:acc-balance-income-line-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $balanceIncomeLines = AccBalanceAndIncomeLine::with('parentLine:id,line_text')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $balanceIncomeLines, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccBalanceAndIncomeLineRequest $request)
    {
        try {
            $balanceAndIncomeLineData = $request->only('line_type','line_text','value_type','parent_id','visible_index','printed_no','business_unit');
            $accBalanceAndIncomeLine     = AccBalanceAndIncomeLine::create($balanceAndIncomeLineData);

            return response()->success('Created Successfully', $accBalanceAndIncomeLine, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accounts\AccBalanceAndIncomeLine  $accBalanceAndIncomeLine
     * @return \Illuminate\Http\Response
     */
    public function show(AccBalanceAndIncomeLine $accBalanceAndIncomeLine)
    {
        try {
            return response()->success('Retrieved Successfully', $accBalanceAndIncomeLine->load('parentLine:id,line_text'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accounts\AccBalanceAndIncomeLine  $accBalanceAndIncomeLine
     * @return \Illuminate\Http\Response
     */
    public function update(AccBalanceAndIncomeLineRequest $request, AccBalanceAndIncomeLine $accBalanceAndIncomeLine)
    {
        try {
            $balanceAndIncomeLineData = $request->only('line_type','line_text','value_type','parent_id','visible_index','printed_no','business_unit');
            $accBalanceAndIncomeLine->update($balanceAndIncomeLineData);

            return response()->success('Updated Successfully', $accBalanceAndIncomeLine, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accounts\AccBalanceAndIncomeLine  $accBalanceAndIncomeLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccBalanceAndIncomeLine $accBalanceAndIncomeLine)
    {
        try {
            $accBalanceAndIncomeLine->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
