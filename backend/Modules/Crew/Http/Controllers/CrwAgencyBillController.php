<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwAgencyBill;
use Modules\Crew\Http\Requests\CrwAgencyBillRequest;

class CrwAgencyBillController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:crw-agency-bill-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-agency-bill-create', ['only' => ['store']]);
        $this->middleware('permission:crw-agency-bill-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-agency-bill-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwAgencyBills = CrwAgencyBill::with('crwAgencyBillLines','crwAgency')->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwAgencyBills, 200);
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
    public function store(CrwAgencyBillRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwAgencyBillData = $request->only('crw_agency_id', 'crw_agency_contract_id', 'applied_date', 'invoice_date', 'invoice_no', 'invoice_type', 'invoice_currency', 'invoice_amount', 'grand_total', 'discount', 'net_amount', 'remarks', 'business_unit');
                $crwAgencyBill     = CrwAgencyBill::create($crwAgencyBillData);
                $crwAgencyBill->crwAgencyBillLines()->createMany($request->crwAgencyBillLines);

                return response()->success('Created Succesfully', $crwAgencyBill, 201);
            });
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwAgencyBill  $crwAgencyBill
     * @return \Illuminate\Http\Response
     */
    public function show(CrwAgencyBill $crwAgencyBill)
    {
        try {
            return response()->success('Retrieved succesfully', $crwAgencyBill->load('crwAgencyBillLines','crwAgency','crwAgencyContract'), 200);
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
     * @param  \App\Models\CrwAgencyBill  $crwAgencyBill
     * @return \Illuminate\Http\Response
     */
    public function update(CrwAgencyBillRequest $request, CrwAgencyBill $crwAgencyBill)
    {
        try {
            DB::transaction(function () use ($request, $crwAgencyBill)
            {
                $crwAgencyBillData = $request->only('crw_agency_id', 'crw_agency_contract_id', 'applied_date', 'invoice_date', 'invoice_no', 'invoice_type', 'invoice_currency', 'invoice_amount', 'grand_total', 'discount', 'net_amount', 'remarks', 'business_unit');
                $crwAgencyBill->update($crwAgencyBillData);
                $crwAgencyBill->crwAgencyBillLines()->delete();
                $crwAgencyBill->crwAgencyBillLines()->createMany($request->crwAgencyBillLines);

                return response()->success('Updated succesfully', $crwAgencyBill, 202);
            });
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwAgencyBill  $crwAgencyBill
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwAgencyBill $crwAgencyBill)
    {
        try {
            $crwAgencyBill->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
