<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
        $start = date($request->date_from);
        $end = date($request->date_to);
        $port = $request->port_name;
        $service = $request->service_id;
        $vessel = $request->vessel_id;

        $voyages = VoyagePair::
        with([
            'firstVoyage' => function ($q) {
                $q->select('id', 'vessel_id', 'service_id', 'voyage');
            },
            'secondVoyage' => function ($q) {
                $q->select('id', 'vessel_id', 'service_id', 'voyage');
            }
        ])
        ->when($vessel, function($voyagePair)use($vessel) {
            $voyagePair->whereHas('firstVoyage', function($voyage)use($vessel) {
                $voyage->where('vessel_id', $vessel);
            });
        })->when($service, function($voyagePair) use($service) {
            $voyagePair->where('service_id', $service);
        })
        ->whereBetween('financial_closing_date', [$start, $end])
        ->with('voyageExpenseEntries.invoice', 'budget.entries.head')
        ->get();

        if(!empty($request->voyagePair)) {
            $voyages = VoyagePair::
            with([
                'firstVoyage' => function ($q) {
                    $q->select('id', 'vessel_id', 'service_id', 'voyage');
                },
                'secondVoyage' => function ($q) {
                    $q->select('id', 'vessel_id', 'service_id', 'voyage');
                }
            ])
            ->with('voyageExpenseEntries.invoice', 'budget.entries.head')
            ->where('id', $request->voyagePair)
            ->get();

        }

        $heads = [];

        $mappingBudgetExpenses = $voyages->map(function ($item, $key) use(&$heads) {

            $item['expenses'] = $item?->voyageExpenseEntries->where('voyage_pairs_id', $item->id)->groupBy('invoice.port');
            $item['globalExpenses'] = $item?->voyageExpenseEntries
                                    ->where('voyage_pairs_id', $item->id)
                                    ->where('invoice.port', null);

            $item['budgetInfo'] = $item?->budget?->entries->map(function ($entries) use($item, &$heads) {
                $entries['expenses'] = $item?->voyageExpenseEntries
                                        ->where('particular_id', $entries->particular_id)
                                        ->where('voyage_pairs_id', $item->id)
                                        ->groupBy('invoice.port');
                

                array_push($heads, [
                    'particular_id' => $entries->particular_id,
                    'name' => $entries->head->name,
                    'port' => $entries->port
                    // 'port' => $item->first()->voyageExpenseEntries->where('particular_id', $entries->particular_id)->first()?->invoice?->port
                ]);

                return $entries;
            });
            return $item;
        });
        
        // return $mappingBudgetExpenses;
        /* Unique Ports */

        $allPorts = collect($heads)->pluck('port')->filter()->unique()->values();

        if(!empty($request->port_name)) {
            $allPorts = [$request->port_name];
        }

        // return $heads;

        /* Port Wise Cost Heads */
        $elements = PortExpenditureHead::with('voyageExpenditureHead')->whereIn('port', $allPorts)->orderBy('voyage_expenditure_head_id', 'asc')->get();

        $headInfo = [];
        $headIds = [];
        $elements->map(function($item, $key) use(&$headIds, &$headInfo) {
            if($item->voyageExpenditureHead->head_id == NULL) {
                array_push($headInfo, [
                    'port' => $item->port,
                    'head_id' => $item->voyageExpenditureHead->id
                ]);
                array_push($headIds, $item->voyageExpenditureHead->id);
            }
        });
        
        $globalHeads = VoyageExpenditureHead::where(['is_global' =>  1, 'head_id' => null])->with('subheads')->get();

        $uniqueHeadIds = collect($headIds)->unique()->values();
        $portWiseHeads = $elements->map(function($item) {
            $data['port'] = $item['port'];
            $data['head_id'] = $item['voyage_expenditure_head_id'];
            $name = (empty($item->voyageExpenditureHead?->head?->name)) ? $item->voyageExpenditureHead->name : $item->voyageExpenditureHead->name .' ('. $item->voyageExpenditureHead?->head?->name.')';
            $data['name'] = $name;
            return $data;
        })->groupBy('port');

        // return response()->json($voyages, 200);
        $view = view('finance.revenue-and-expense.budget-vs-expense', compact('voyages', 'portWiseHeads', 'globalHeads', 'mappingBudgetExpenses'))->render();

        // return $view;
        return response()->json([
            'view' => $view
        ], 200);

    }
}
