<?php

namespace Modules\SupplyChain\Http\Controllers;

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

                    'received' => $items->whereNull('parent_id')->whereBetween('date', [request()->from_date, request()->end_date])?->sum('quantity') - (empty($result['opening_stock'])? $items->where('date', '=', request()->from_date)->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity ?? 0 : 0),

                    'consumed' => ($items->whereNotNull('parent_id')
                        ->whereBetween('date', [request()->from_date, request()->end_date])
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
            ->where('scm_warehouse_id', request()->scm_warehouse_id)
            ->where('scm_material_id', request()->scm_material_id)
            ->whereBetween('date', [request()->from_date, request()->end_date])
            ->get()
            ->groupBy('date')->values();


            foreach ($result as $items) {
                $adas = [
                    'date' => $items[0]->date,
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
            $datas= ScmPr::with('scmPoLines.scmPo.scmMrrs')->where('id', $request->scm_pr_id)->get();
            
            
            // ->map(function($items){
            //     $datas= $items;

            //     $items->map(function($item){
            //         $data= [];
            //         $mrrData= [];
            //         $data['ref_no']= $item->ref_no;
            //         $data['status']= $item->status;
            //         $data['closed_at']= $item->closed_at;
            //         $data['date']= $item->date;

            //         $mrrData['ref_no']= $item->ref_no;
            //         $mrrData['status']= $item->status;
            //         $mrrData['closed_at']= $item->closed_at;
            //         $mrrData['date']= $item->date;

            //         $mrrData
            //     });
            // });

            return response()->success('data', $datas, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }
}
