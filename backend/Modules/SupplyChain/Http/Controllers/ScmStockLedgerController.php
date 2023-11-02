<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SupplyChain\Entities\ScmStockLedger;

class ScmStockLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('supplychain::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('supplychain::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supplychain::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supplychain::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Retrieves the current stock quantity for a specific material.
     *
     * @param int $scm_material_id
     * @param int $scm_warehouse_id
     * @return JsonResponse
     */
    public function currentStock(): JsonResponse
    {
        $totalQuantity = ScmStockLedger::query()
            ->where([
                'scm_material_id' => request()->scm_material_id,
                'scm_warehouse_id' => request()->scm_warehouse_id
            ])
            ->sum('quantity');

        return response()->success('Total Quantity', $totalQuantity, 200);
    }
}
