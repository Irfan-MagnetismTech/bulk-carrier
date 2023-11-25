<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccLoan;
use Modules\Accounts\Entities\AccLoanReceived;
use Modules\Accounts\Http\Requests\AccLoanReceivedRequest;

class AccLoanReceivedController extends Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {
            $accLoanReceiveds = AccLoanReceived::with('loan')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $accLoanReceiveds, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     */
    public function store(AccLoanReceivedRequest $request)
    {
        try {
            $accLoanReceived = AccLoanReceived::create($request->all());

            return response()->success('Created Successfully', $accLoanReceived, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccLoanReceived $accLoanReceived
     */
    public function show(AccLoanReceived $accLoanReceived)
    {
        try {
            return response()->success('Retrieved Successfully', $accLoanReceived->load('loan', 'bank'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @param AccLoanReceived $accLoanReceived
     */
    public function update(AccLoanReceivedRequest $request, AccLoanReceived $accLoanReceived)
    {
        try {
            $accLoanReceived->update($request->all());

            return response()->success('Updated Successfully', $accLoanReceived, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param AccLoanReceived $accLoanReceived
     */
    public function destroy(AccLoanReceived $accLoanReceived)
    {
        try {
            $accLoanReceived->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
