<?php

namespace Modules\SupplyChain\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmPr;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmStockLedger;

class ScmReportController extends Controller
{
    // for inventory report api
    public function inventoryReport(Request $request): JsonResponse
    {
        try {

            $datas = [];
            $result = ScmStockLedger::query()
                ->where('scm_warehouse_id', request()->scm_warehouse_id)
                ->get()
                ->groupBy('scm_material_id');

            foreach ($result as $items) {
                $adas = [
                    'scmMaterial' => $items[0]->scmMaterial,
                    'scm_warehouse_id' => $items[0]->scm_warehouse_id,

                    'opening_stock' => $items->where('date', '<', request()->from_date)?->sum('quantity') ?? ($items->where('date', '=', request()->from_date)->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity ?? 0),

                    'received' => $items->whereNull('parent_id')->whereBetween('date', [request()->from_date, request()->to_date])?->sum('quantity') - (empty($result['opening_stock'])? $items->where('date', '=', request()->from_date)->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity ?? 0 : 0),

                    'consumed' => ($items->whereNotNull('parent_id')
                        ->whereBetween('date', [request()->from_date, request()->to_date])
                        ->sum('quantity') * -1),
                ];

                if($adas['opening_stock'] < 0){
                    $adas['opening_stock']=0;
                }

                $adas['closing_stock'] = max(0, $adas['opening_stock'] + $adas['received'] - $adas['consumed']);

                $datas[] = $adas;
            }


            return response()->success('data', $datas, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    // for stock history report api
    public function stockHistoryReport(Request $request): JsonResponse
    {
        try {
            $datas = [];
            $result = ScmStockLedger::query()
            ->where('scm_warehouse_id', $request->scm_warehouse_id)
            ->where('scm_material_id', $request->scm_material_id)
            ->whereBetween('date', [$request->from_date, $request->to_date])
            ->get()
            ->groupBy(function($record) {
                return Carbon::parse($record->date)->format('Y-m-d');
            })->values();

            dd($result);
            foreach ($result as $items) {
                $adas = [
                    'date' => Carbon::parse($items[0]->date)->format('Y-m-d'),
                    'scmMaterial' => $items[0]->scmMaterial,
                    'scm_warehouse_id' => $items[0]->scm_warehouse_id,

                    'opening_stock' => $items->sum('quantity') ?? ($items->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity ?? 0),

                    'received' => $items->whereNull('parent_id')->sum('quantity') - (empty($result['opening_stock'])? $items->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity ?? 0 : 0),

                    'issued' => ($items->whereNotNull('parent_id')                       
                        ->sum('quantity') * -1),
                ];

                if($adas['opening_stock'] < 0){
                    $adas['opening_stock']=0;
                }

                $adas['closing_stock'] = max(0, $adas['opening_stock'] + $adas['received'] - $adas['issued']);

                $datas[] = $adas;
            }
            // dd($result);

            return response()->success('data', $datas, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function purchaseRequisitionReport(Request $request): JsonResponse
    {
        try {
            $scm_prs = ScmPr::query()
                ->with(
                    'scmPrLines.scmMaterial',
                    'scmWarehouse',
                    'closedBy',
                    'createdBy',
                    'scmPrLines.closedBy',
                    'scmPrLines.createdBy'
                )
                ->when(request()->has('scm_warehouse_id'), function ($query) {
                    $query->where('scm_warehouse_id', request()->scm_warehouse_id);
                })
                ->when(request()->has('purchase_center'), function ($query) {
                    $query->where('purchase_center', request()->purchase_center);
                })
                ->when(request()->has('status'), function ($query) {
                    $query->where('status', request()->status);
                })
                ->whereBetween('raised_date', [$request->from_date, $request->to_date])
                ->get();

            return response()->success('Data list', $scm_prs, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

}
