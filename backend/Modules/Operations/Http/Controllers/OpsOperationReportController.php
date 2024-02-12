<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use App\Services\FileUploadService;
use Spatie\Permission\Traits\HasRoles;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsExpenseHead;
use Modules\Operations\Entities\OpsVesselExpenseHead;
use Modules\Operations\Entities\OpsVoyageExpenditureEntry;

class OpsOperationReportController extends Controller
{
    use HasRoles;

    function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:ops-budget-vs-expense-report', ['only' => ['budgetVsExpenseReport']]);
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
                    ->with('opsVesselBunkers.stockable')
                    ->get();

        if(!empty($request->ops_voyage_id)) {
            $voyages = OpsVoyage::whereIn('id', $ops_voyage_id)
            ->with('opsVesselBunkers.stockable')
                        // ->with('voyageExpenseEntries.invoice', 'budget.entries.head')
                        ->get();

        }

        if(count($voyages) < 1) {
            $error= [
                'message'=>'Report not found.',
                'errors'=>[
                    'type'=>['Report not found.',]
                    ]
                ];
            return response()->json($error, 422);
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
        $voyages->map(function($voyage) {
            $stockCost = $this->calculateStockOutCost($voyage->opsVesselBunkers->where('type', 'Stock Out'));
            $voyage->amount_bdt = $stockCost['amount_bdt'];
            return $voyage;
        });

        // return response()->json($voyages, 200);
        // return view('operations::reports.budget-vs-expense-report', 
        // compact('heads', 'voyages', 'expenseEntries'));

        $view = view('operations::reports.budget-vs-expense-report', 
                    compact('heads', 'voyages', 'expenseEntries'))->render();

        // return $view;
        return response()->json([
            'value' => $view
        ], 200);

    }

    private function calculateStockOutCost($vesselBunkers) {

        // $voyage->opsVesselBunkers

        $amount_bdt = 0;
        $amount_usd = 0;

        $vesselBunkers->map(function($bunker) use(&$amount_bdt) {
            $bunker->stockable->map(function($stock) use(&$amount_bdt) {
                $amount_bdt += abs($stock->quantity) * $stock->gross_unit_price;
            });
        });

        return ['amount_bdt' => $amount_bdt];

    }
}
