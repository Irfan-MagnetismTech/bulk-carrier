<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmWarehouse;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\Operations\Entities\OpsVesselBunker;
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

    // public function vesselBunkerReport(Request $request) {

    //     $business_unit = $request->business_unit;
    //     $ops_vessel_id = $request->ops_vessel_id;
    //     $start = date($request->start);
    //     $end = date($request->end);

    //     $voyages = OpsVoyage::query()
    //     // ->whereBetween('transit_date', [$start, $end])
    //     ->whereBetween('transit_date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
    //     ->where('ops_vessel_id', $ops_vessel_id)
    //     ->where('business_unit', $business_unit)
    //     ->with('opsVesselBunkers.stockable', 'opsVessel')
    //     ->get();

    //     $voyages = $voyages->map(function($voyage) {
    //         $bunkers = $voyage->opsVesselBunkers->groupBy('type');
    //         $voyage['bunkers'] = $bunkers;
    //         return $voyage;
    //     });

    //     // dd($voyages);

    //     $allBunkers = OpsVesselBunkerService::getBunkers($ops_vessel_id, null);


    //     return view('operations::reports.single-vessel-bunker-report')->with([
    //         'allBunkers' => $allBunkers,
    //         'voyages' => $voyages
    //     ]);

    //     return response()->json([
    //         'value' => $view
    //     ], 200);
    // }


    public function vesselBunkerReportBackup(Request $request)
    {

        $business_unit = $request->business_unit;
        $ops_vessel_id = $request->ops_vessel_id;
        $start = date($request->start);
        $end = date($request->end);

        $vesselBunkers = OpsVesselBunker::where('ops_vessel_id', $ops_vessel_id)
            ->with('opsVessel', 'opsVoyage', 'stockable')
            ->whereBetween('date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
            ->get();

        $vesselBunkers = $vesselBunkers->groupBy('ops_voyage_id');
            
        // dd($output[37]->groupBy('type'));
        $scm_warehouse_id = ScmWarehouse::where('ops_vessel_id', $ops_vessel_id)->first()->id;

        $allBunkers = OpsVesselBunkerService::getBunkers($ops_vessel_id, null)->map(function ($material) use ($start, $end, $scm_warehouse_id) {
            $material['previous_stock'] = CurrentStock::count($material['scm_material_id'], $scm_warehouse_id, $start);
            $material['final_stock'] = CurrentStock::count($material['scm_material_id'], $scm_warehouse_id, $end);
            $material['scm_warehoust_id'] = $scm_warehouse_id;
            return $material;
        });

        // dd($vesselBunkers);

        // $scm_material_id, $scm_warehouse_id, $toDate = null

        return view('operations::reports.single-vessel-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'stockRecords' => $vesselBunkers
        ]);

        $view = view('operations::reports.single-vessel-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'stockRecords' => $vesselBunkers
        ])->render();

        return response()->json([
            'value' => $view
        ], 200);
    }


    public function vesselBunkerReport(Request $request)
    {

        $business_unit = $request->business_unit;
        $ops_vessel_id = $request->ops_vessel_id;
        $ops_voyage_id = $request->ops_voyage_id;
        $type = $request->type;
        $usage_type = $request->usage_type;
        $start = date($request->start);
        $previous_date = Carbon::parse($request->start)->subDay(1)->endOfDay();
        $end = date($request->end);
        // dd($request->all());

        $vesselBunkers = OpsVesselBunker::when(isset($ops_vessel_id), function($query) use ($ops_vessel_id){
                $query->where('ops_vessel_id', $ops_vessel_id);
            })
            ->when(isset($ops_voyage_id), function($query) use ($ops_voyage_id){
                $query->where('ops_voyage_id', $ops_voyage_id);
            })
            ->when(isset($type), function($query) use ($type){
                $query->where('type', $type);
            })
            ->when(isset($usage_type), function($query) use ($usage_type){
                $query->where('usage_type', $usage_type);
            })
            ->with('opsVessel', 'opsVoyage', 'stockable')
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->whereBetween('date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
            ->get();

        if (count($vesselBunkers)<1) {
            $error= [
                'message'=>'Report not found.',
                'errors'=>[
                    'type'=>['Report not found.',]
                    ]
                ];
            return response()->json($error, 422);
        }
        // $vesselBunkers = $vesselBunkers->groupBy('ops_voyage_id');
            
        // dd($output[37]->groupBy('type'));
        $scm_warehouse_id = ScmWarehouse::where('ops_vessel_id', $ops_vessel_id)->first()->id;

        $ops_vessel_ids= $vesselBunkers->pluck('ops_vessel_id')->toArray();
        $allBunkers = OpsVesselBunkerService::getBunkers($ops_vessel_ids, null)->map(function ($material) use ($previous_date, $end, $scm_warehouse_id) {
            $material['previous_stock'] = CurrentStock::count($material['scm_material_id'], $scm_warehouse_id, $previous_date);
            $material['final_stock'] = CurrentStock::count($material['scm_material_id'], $scm_warehouse_id, $end);
            $material['scm_warehoust_id'] = $scm_warehouse_id;
            return $material;
        });

        
        // dd($allBunkers);

        // $scm_material_id, $scm_warehouse_id, $toDate = null

        return view('operations::reports.vessel-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'stockRecords' => $vesselBunkers
        ]);

        $view = view('operations::reports.vessel-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'stockRecords' => $vesselBunkers
        ])->render();

        return response()->json([
            'value' => $view
        ], 200);
    }

    public function businessUnitWiseBunkerReport(Request $request) {

        $business_unit = $request->business_unit;
        $ops_vessels = OpsVessel::where('business_unit', $business_unit)->get();
        
        $fromDate = $request->start ?? Carbon::parse('2023-01-01');
        $toDate = $request->end ?? Carbon::now();
        $prevousDate = Carbon::parse($fromDate)->subDay(1)->endOfDay();

        $voyages = OpsVoyage::select('ops_vessel_id', DB::raw('count(id) as voyage_count'))
        ->where('business_unit', $business_unit)
        ->groupBy('ops_vessel_id')
        ->with(['opsVessel.scmWareHouse','opsVesselBunkers.stockable'])
        ->get();

        // Get bunkers used in vessels for the business unit
        $allBunkers = OpsVesselBunkerService::getBunkers(null, $business_unit);

        $voyages = $voyages->map(function($voyage) use ($allBunkers, $prevousDate, $fromDate, $toDate) {
            $voyagesWithBunkers = [
                'vessel_name' => $voyage->opsVessel->name,
                'voyage_count' => $voyage->voyage_count
            ];
            $warehouse_id = $voyage->opsVessel->scmWareHouse?->id;
            foreach ($allBunkers as $bunker) {
                $bunker_id = $bunker['scm_material_id'];
                $voyagesWithBunkers['previous_stock'][$bunker_id] = CurrentStock::count($bunker_id, $warehouse_id, $prevousDate);
                $voyagesWithBunkers['current_stock'][$bunker_id] = CurrentStock::count($bunker_id, $warehouse_id, $toDate);
                $voyagesWithBunkers['stock_in'][$bunker_id] = CurrentStock::countStockIn($bunker_id, $warehouse_id, $fromDate, $toDate);
                $voyagesWithBunkers['stock_out'][$bunker_id] = CurrentStock::countStockOut($bunker_id, $warehouse_id, $fromDate, $toDate);
            }

            return $voyagesWithBunkers;
        });

        // dd(count($allBunkers));

        $view = view('operations::reports.business-unit-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'voyages' => $voyages
        ])->render();

        return response()->json([
            'value' => $view
        ], 200);

    }

    public function businessUnitWiseBunkerReportHorizontalBackup(Request $request) {

        $business_unit = $request->business_unit;
        $ops_vessels = OpsVessel::where('business_unit', $business_unit)->get();
        
        $fromDate = $request->start ?? Carbon::parse('2023-01-01');
        $toDate = $request->end ?? Carbon::now();
        $prevousDate = Carbon::parse($fromDate)->subDay(1)->endOfDay();

        $voyages = OpsVoyage::select('ops_vessel_id', DB::raw('count(id) as voyage_count'))
        ->where('business_unit', $business_unit)
        ->groupBy('ops_vessel_id')
        ->with(['opsVessel.scmWareHouse','opsVesselBunkers.stockable'])
        ->get();

        // Get bunkers used in vessels for the business unit
        $allBunkers = OpsVesselBunkerService::getBunkers(null, $business_unit);

        $voyages = $voyages->map(function($voyage) use ($allBunkers, $prevousDate, $fromDate, $toDate) {
            $voyagesWithBunkers = [
                'vessel_name' => $voyage->opsVessel->name,
                'voyage_count' => $voyage->voyage_count
            ];
            $warehouse_id = $voyage->opsVessel->scmWareHouse?->id;
            foreach ($allBunkers as $bunker) {
                $bunker_id = $bunker['scm_material_id'];
                $voyagesWithBunkers['previous_stock'][$bunker_id] = CurrentStock::count($bunker_id, $warehouse_id, $prevousDate);
                $voyagesWithBunkers['current_stock'][$bunker_id] = CurrentStock::count($bunker_id, $warehouse_id, $toDate);
                $voyagesWithBunkers['stock_in'][$bunker_id] = CurrentStock::countStockIn($bunker_id, $warehouse_id, $fromDate, $toDate);
                $voyagesWithBunkers['stock_out'][$bunker_id] = CurrentStock::countStockOut($bunker_id, $warehouse_id, $fromDate, $toDate);
            }

            return $voyagesWithBunkers;
        });

        $view = view('operations::reports.business-unit-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'voyages' => $voyages
        ])->render();

        return response()->json([
            'value' => $view
        ], 200);

    }

    public function vesselBunkerReportHorizontalBackup(Request $request)
    {

        $business_unit = $request->business_unit;
        $ops_vessel_id = $request->ops_vessel_id;
        $ops_voyage_id = $request->ops_voyage_id;
        $type = $request->type;
        $usage_type = $request->usage_type;
        $start = date($request->start);
        $previous_date = Carbon::parse($request->start)->subDay(1)->endOfDay();
        $end = date($request->end);
        // dd($request->all());

        $vesselBunkers = OpsVesselBunker::when(isset($ops_vessel_id), function($query) use ($ops_vessel_id){
                $query->where('ops_vessel_id', $ops_vessel_id);
            })
            ->when(isset($ops_voyage_id), function($query) use ($ops_voyage_id){
                $query->where('ops_voyage_id', $ops_voyage_id);
            })
            ->when(isset($type), function($query) use ($type){
                $query->where('type', $type);
            })
            ->when(isset($usage_type), function($query) use ($usage_type){
                $query->where('usage_type', $usage_type);
            })
            ->with('opsVessel', 'opsVoyage', 'stockable')
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->whereBetween('date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
            ->get();

        if (count($vesselBunkers)<1) {
            $error= [
                'message'=>'Report not found.',
                'errors'=>[
                    'type'=>['Report not found.',]
                    ]
                ];
            return response()->json($error, 422);
        }
        // $vesselBunkers = $vesselBunkers->groupBy('ops_voyage_id');
            
        // dd($output[37]->groupBy('type'));
        $scm_warehouse_id = ScmWarehouse::where('ops_vessel_id', $ops_vessel_id)->first()->id;

        $ops_vessel_ids= $vesselBunkers->pluck('ops_vessel_id')->toArray();
        $allBunkers = OpsVesselBunkerService::getBunkers($ops_vessel_ids, null)->map(function ($material) use ($previous_date, $end, $scm_warehouse_id) {
            $material['previous_stock'] = CurrentStock::count($material['scm_material_id'], $scm_warehouse_id, $previous_date);
            $material['final_stock'] = CurrentStock::count($material['scm_material_id'], $scm_warehouse_id, $end);
            $material['scm_warehoust_id'] = $scm_warehouse_id;
            return $material;
        });

        


        // $scm_material_id, $scm_warehouse_id, $toDate = null

        // return view('operations::reports.vessel-bunker-report')->with([
        //     'allBunkers' => $allBunkers,
        //     'stockRecords' => $vesselBunkers
        // ]);

        $view = view('operations::reports.vessel-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'stockRecords' => $vesselBunkers
        ])->render();

        return response()->json([
            'value' => $view
        ], 200);
    }
}
