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


}
