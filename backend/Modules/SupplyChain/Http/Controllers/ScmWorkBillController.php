<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWorkBill;
use Modules\SupplyChain\Http\Requests\ScmWorkBillRequest;

class ScmWorkBillController extends Controller
{
      /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $scm_work_bills = ScmWorkBill::query()
                ->globalSearch($request->all());

            return response()->success('Data list', $scm_work_bills, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmWorkBillRequest $request): JsonResponse
    {
        try {
            $scm_work_bill = ScmWorkBill::create($request->all());

            return response()->success('Data created succesfully', $scm_work_bill, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmWorkBill $work_bill
     * @return JsonResponse
     */
    public function show(ScmWorkBill $work_bill): JsonResponse
    {
        try {
            return response()->success('data', $work_bill, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmWorkBillRequest $request
     * @param ScmWorkBill $work_bill
     * @return JsonResponse
     */
    public function update(ScmWorkBillRequest $request, ScmWorkBill $work_bill): JsonResponse
    {
        try {
            $work_bill->update($request->all());

            return response()->success('Data updated sucessfully!', $work_bill, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmWorkBill $work_bill
     * @return JsonResponse
     */
    public function destroy(ScmWorkBill $work_bill): JsonResponse
    {
        try {
            $work_bill->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

}
