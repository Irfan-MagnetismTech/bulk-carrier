<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Services\OpsVesselBunkerService;

class OpsBunkerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('operations::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('operations::create');
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
        return view('operations::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('operations::edit');
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

    public function vesselBunkerReport(Request $request) {

        $business_unit = $request->business_unit;
        $ops_vessel_id = $request->ops_vessel_id;
        $start = date($request->start);
        $end = date($request->end);

        $voyages = OpsVoyage::query()
        // ->whereBetween('transit_date', [$start, $end])
        ->whereBetween('transit_date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
        ->where('ops_vessel_id', $ops_vessel_id)
        ->where('business_unit', $business_unit)
        ->with('opsVesselBunkers.stockable')
        ->get();

        $voyages = $voyages->map(function($voyage) {
            return $voyage->opsVesselBunkers->groupBy('type');
        });

        $allBunkers = OpsVessel::with('opsBunkers.scmMaterial')->where('id', $ops_vessel_id)->first();

        $allBunkers = OpsVesselBunkerService::getBunkers($ops_vessel_id);

        dd($allBunkers);

        $voyageIds = $voyages->pluck('id');

        return view('operations::reports.single-vessel-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'voyages' => $voyages
        ]);

        return response()->json([
            'value' => $view
        ], 200);
    }
}
