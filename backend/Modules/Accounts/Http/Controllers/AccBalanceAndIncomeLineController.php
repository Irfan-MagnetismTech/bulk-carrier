<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccBalanceAndIncomeLine;

class AccBalanceAndIncomeLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrewRanks = AccBalanceAndIncomeLine::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwCrewRanks, 200);
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
    public function store(Request $request)
    {
        try {
            $balanceAndIncomeLineData = $request->only('line_type','line_text','value_type','parent_id','visible_index','printed_no','business_unit');
            $accBalanceAndIncomeLine     = AccBalanceAndIncomeLine::create($balanceAndIncomeLineData);

            return response()->success('Created Succesfully', $accBalanceAndIncomeLine, 201);
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
            return response()->success('Retrieved succesfully', $accBalanceAndIncomeLine, 200);
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
    public function update(Request $request, AccBalanceAndIncomeLine $accBalanceAndIncomeLine)
    {
        try {
            $balanceAndIncomeLineData = $request->only('line_type','line_text','value_type','parent_id','visible_index','printed_no','business_unit');
            $accBalanceAndIncomeLine->update($balanceAndIncomeLineData);

            return response()->success('Updated succesfully', $accBalanceAndIncomeLine, 202);
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

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }        
    }
}
