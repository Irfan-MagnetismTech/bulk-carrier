<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Entities\OpsVoyageExpenditureEntry;

class OpsOperationReportController extends Controller
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

    public function budgetVsExpenseReport(Request $request) {
        $start = date($request->start);
        $end = date($request->end);
        $port = $request->port;
        $ops_vessel_id = $request->ops_vessel_id;
        $ops_voyage_id = $request->ops_voyage_id;

        $voyages = OpsVoyage::when($ops_vessel_id, function($voyage) use ($ops_vessel_id) {
                        $voyage->where('ops_vessel_id', $ops_vessel_id);
                    })
                    ->whereBetween('sail_date', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
                    // ->with('voyageExpenseEntries.invoice', 'budget.entries.head')
                    ->get();

        if(!empty($request->ops_voyage_id)) {
            $voyages = OpsVoyage::whereIn('id', $ops_voyage_id)
                        // ->with('voyageExpenseEntries.invoice', 'budget.entries.head')
                        ->get();

        }

        $voyageIds = $voyages->pluck('id');
        $vesselIds = $voyages->pluck('ops_vessel_id')->unique()->values()->toArray();

        $vesselExpenseHeads = OpsVesselExpenseHead::whereIn('ops_vessel_id', $vesselIds)->get()->pluck('ops_expense_head_id')->unique()->toArray();

        $findingHeads = OpsExpenseHead::whereIn('id', $vesselExpenseHeads)->with('opsSubHeads')
                        ->get()
                        ->pluck('head_id')
                        ->filter()
                        ->unique()
                        ->values()->toArray();

        $expenseHeads = OpsExpenseHead::whereIn('id', $findingHeads)->where('head_id', null)->with('opsSubHeads')->get();

        $allHeads = [];

        $expenseHeads->map(function($item) use($vesselExpenseHeads, &$allHeads) {

            $result = $item->opsSubHeads->map(function($subhead) use($vesselExpenseHeads, &$allHeads) {
                if(in_array($subhead['id'], $vesselExpenseHeads)) {
                    $subhead['ops_expense_head_id'] = $subhead['id'];
                    $subhead['type'] = 'subhead';
                    return $subhead;
                }
            })->filter()->values()->all();

            $item['ops_expense_head_id'] = $item['id'];
            $item['type'] = 'head';

            data_forget($item, 'opsSubHeads');
            $item->opsSubHeads = $result;

            if(!empty($item->opsSubHeads)) {
                array_push($allHeads, $result);
            } else {
                array_push($allHeads, data_forget($vessel_expense_heads, 'opsSubHeads'));
            }
            return $item;
        });

        $heads = collect($allHeads)->flatten();
        
        $expenseEntries = OpsVoyageExpenditureEntry::with('opsExpenseHead.opsSubHeads')
                    // ->where('port_code', $port)
                    ->whereIn('ops_voyage_id', $voyageIds)->get();
        
        // return $mappingBudgetExpenses;
        /* Unique Ports */

        // return response()->json($voyages, 200);
        $view = view('operations::reports.budget-vs-expense-report', 
                    compact('heads', 'voyages', 'expenseEntries'))->render();

        // return $view;
        return response()->json([
            'value' => $view
        ], 200);

    }
}
