<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccLoan;
use Modules\Accounts\Http\Requests\AccLoanRequest;

class AccLoanController extends Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $accLoans = AccLoan::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accLoans, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccLoanRequest $request
     */
    public function store(AccLoanRequest $request)
    {
        try {
            $accLoanData = $request->only('loanable_type', 'loanable_id', 'loan_type', 'loan_number', 'loan_name', 'total_sanctioned', 'sanctioned_limit', 'total_installment', 'interest_rate', 'opening_date', 'maturity_date', 'emi_date', 'emi_amount', 'loan_purpose', 'mortgage', 'remarks', 'business_unit');

            $accLoan = AccLoan::create($accLoanData);

            return response()->success('Created Successfully', $accLoan, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccLoan $accLoan
     */
    public function show(AccLoan $accLoan)
    {
        try {
            return response()->success('Retrieved Successfully', $accLoan, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccLoanRequest $request
     * @param AccLoan $accLoan
     */
    public function update(AccLoanRequest $request, AccLoan $accLoan)
    {
        try {
            $accLoan->update($request->all());

            return response()->success('Updated Successfully', $accLoan, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccLoan $accLoan
     */
    public function destroy(AccLoan $accLoan)
    {
        try {
            $accLoan->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
