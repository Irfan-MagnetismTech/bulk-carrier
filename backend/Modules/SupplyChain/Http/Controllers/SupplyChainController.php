<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SupplyChain\Entities\ScmStockLedger;

class SupplyChainController extends Controller
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

    public function getCurrentStock($materialId = 1, $warehouseId = 1)
    {
        $currentStock1 = ScmStockLedger::query()
            ->where('scm_material_id', $materialId)
            ->where('scm_warehouse_id', $warehouseId)
            ->get()
            ->groupBy('received_date');

        $currentStock2 = ScmStockLedger::query()
            ->where('scm_material_id', $materialId)
            ->where('scm_warehouse_id', $warehouseId)
            ->whereNull('receivable_id')
            ->get()
            ->groupBy('received_date');

        dd($currentStock1); 
        // return response()->json($currentStock);
    }
}
