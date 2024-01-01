<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Entities\OpsVoyage;
use Modules\Operations\Entities\OpsVoyageExpenditureEntry;

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

    public function expenditureReport(Request $request)
    {
        $start = date($request->start);
        $end = date($request->end);
        $port = $request->port;
        $business_unit = $request->business_unit;

        $voyages = OpsVoyage::whereBetween('sail_date', [$start, $end])
                            ->where('business_unit', $business_unit)
                            ->get();

        $voyageIds = $voyages->pluck('id');
        $vesselIds = $voyages->pluck('ops_vessel_id')->unique()->values()->toArray();

        $vesselExpenseHeads = OpsVesselExpenseHead::whereIn('ops_vessel_id', $vesselIds)->get()->pluck('ops_expense_head_id')->unique()->toArray();

        $elements = OpsExpenseHead::whereIn('id', $vesselExpenseHeads)->where('head_id', null)->with('opsSubHeads')->get();

        $gettingRealChild = $elements->map(function ($item) use($vesselExpenseHeads) {
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

        $headInfo = [];
        $headIds = [];
        
        $entries = OpsVoyageExpenditureEntry::with('opsExpenseHead.opsSubHeads')
                    ->where('port_code', $port)
                    ->whereIn('ops_voyage_id', $voyageIds)->get();

        $entryGroup = $entries->map(function($item) {
                $item['type'] = (!empty($item['head']['head_id'])) ? 'subhead' : 'head';
                $item['port'] = $item['invoice']['port'];
                $item['voyage'] = $item->opsVoyage->voyage_sequence;
                $item['vessel'] = $item->opsVoyage->opsVessel->name;
                $item['arrival'] = $item->opsVoyage->opsVoyagePortSchedules->first()->sail_date;
                $item['sailing'] = $item->opsVoyage->opsVoyagePortSchedules->first()->transit_date;
                return $item;
        });

        return view('reports.expense-report')->with([
            'realSubheads' => $gettingRealChild,
            'entryMultiGroup' => $entryGroup,
        ]);

        // $view = view('reports.expense-report')->with([
        //     'portWiseHeads' => collect($portWiseHeads),
        //     'portWiseHeadIds' => $portWiseHeadIds,
        //     'realSubheads' => $gettingRealChild,
        //     'entryMultiGroup' => $entryMultiGroup,
        //     'entryGroupWithoutPort' => $entryGroupWithoutPort
        // ])->render();

        // return response()->json([
        //     'view' => $view
        // ], 200);

        // return Excel::download(new VoyageExpenditure($heads, $filteredVoyage), 'voyage_expenditure_report.xlsx');
    }
}
