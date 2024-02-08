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


}
