<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Entities\OpsVessel;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Entities\OpsVoyageExpenditureEntry;
use Modules\Operations\Services\OpsVesselBunkerService;
use Modules\SupplyChain\Services\CurrentStock;
use Modules\SupplyChain\Services\StockLedgerData;

class OpsExpenseReportController extends Controller
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

    public function portWiseExpenditureReport(Request $request) {
        $start = date($request->start);
        $end = date($request->end);
        $port = $request->port;
        $business_unit = $request->business_unit;

        $voyages = OpsVoyage::query()
                            // ->whereBetween('transit_date', [$start, $end])
                            ->whereBetween('transit_date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])

                            ->where('business_unit', $business_unit)
                            ->get();

        $voyageIds = $voyages->pluck('id');
        $vesselIds = $voyages->pluck('ops_vessel_id')->unique()->values()->toArray();

        // dd($voyageIds);

        $vesselExpenseHeads = OpsVesselExpenseHead::whereIn('ops_vessel_id', $vesselIds)->get()->pluck('ops_expense_head_id')->unique()->toArray();

        $findingHeads = OpsExpenseHead::whereIn('id', $vesselExpenseHeads)->with('opsSubHeads')
                        ->get()
                        ->pluck('head_id')
                        ->filter()
                        ->unique()
                        ->values()->toArray();

        $heads = OpsExpenseHead::whereIn('id', $findingHeads)->where('head_id', null)->with('opsSubHeads')->get();

        $heads->map(function ($item) use($vesselExpenseHeads) {
                                    $subheads = $item->opsSubHeads->map(function($subhead) use($vesselExpenseHeads) {
                                        if(in_array($subhead['id'],$vesselExpenseHeads)) {
                                            return $subhead;
                                        } else {
                                            return null;
                                        }
                                    })->filter();

                                    data_forget($item, 'opsSubHeads');
                                    $item['opsSubHeads'] = $subheads;
                                    return $item;
                                        
                                });

        
        $entries = OpsVoyageExpenditureEntry::with('opsExpenseHead.opsSubHeads')
                    ->where('port_code', $port)
                    ->whereIn('ops_voyage_id', $voyageIds)->get();

        // dd($entries);

        $entryGroups = $entries->map(function($item) {
                $item['type'] = (!empty($item['opsExpenseHead']['head_id'])) ? 'subhead' : 'head';
                $item['voyage'] = $item->opsVoyage->voyage_sequence;
                $item['vessel'] = $item->opsVoyage->opsVessel->name;
                $item['sail_date'] = $item->opsVoyage->sailing_date;
                $item['transit_date'] = $item->opsVoyage->transit_date;
                return $item;
        })->groupBy('ops_voyage_id','ops_expense_head_id');

        // dd($entryGroups);

        $view = view('operations::reports.expense-report')->with([
            'entryGroups' => $entryGroups,
            'port' => $port,
            'heads' => $heads,
            'voyages' => $voyages
        ])->render();

        // $view = view('reports.expense-report')->with([
        //     'portWiseHeads' => collect($portWiseHeads),
        //     'portWiseHeadIds' => $portWiseHeadIds,
        //     'realSubheads' => $gettingRealChild,
        //     'entryMultiGroup' => $entryMultiGroup,
        //     'entryGroupWithoutPort' => $entryGroupWithoutPort
        // ])->render();

        return response()->json([
            'value' => $view
        ], 200);

        // return Excel::download(new VoyageExpenditure($heads, $filteredVoyage), 'voyage_expenditure_report.xlsx');
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
                $voyagesWithBunkers['current_stock'][$bunker_id] = CurrentStock::count($bunker_id, $warehouse_id);
                $voyagesWithBunkers['stock_in'][$bunker_id] = CurrentStock::countStockIn($bunker_id, $warehouse_id, $fromDate, $toDate);
                $voyagesWithBunkers['stock_out'][$bunker_id] = CurrentStock::countStockOut($bunker_id, $warehouse_id, $fromDate, $toDate);
            }

            return $voyagesWithBunkers;
        });

        return view('operations::reports.business-unit-bunker-report')->with([
            'allBunkers' => $allBunkers,
            'voyages' => $voyages
        ]);

    }
}
