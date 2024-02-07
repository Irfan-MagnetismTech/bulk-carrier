<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\SupplyChain\Entities\ScmStockLedger;

class ScmReportController extends Controller
{

    public function inventoryReport(Request $request): JsonResponse
    {
        try {
           
            // $result = ScmStockLedger::query()           
            // ->where('scm_warehouse_id', request()->scm_warehouse_id)            
            // ->get()
            // ->groupBy('scm_material_id')
            // ->map(function($items, $key) use ($datas){          

            //     $adas=null;
            //     foreach($items as $item){
            //         $adas['scmMaterial']= $item->first()->scmMaterial;
            //         $adas['scm_warehouse_id']= $item->first()->scm_warehouse_id;
            //         $adas['opening_stock']= $item->where('date', '<', request()->from_date)?->sum('quantity') ?? ($item->where('date', '=', request()->from_date)->first()?->quantity ?? 0);

            //         $adas['received']= $item->whereNull('recievable_type')->whereBetween('date', [request()->from_date, request()->end_date])?->sum('quantity') - (empty($result['opening_stock'])? $item->where('date', '=', request()->from_date)->first()?->quantity : 0);

            //         $adas['consumed']= $item->whereNotNull('recievable_type')->whereBetween('date', [request()->from_date, request()->end_date])?->sum('quantity') * -1;

            //         break;
            //     }

            //     data_forget($items, $key);
            //     $datas = $adas;
            //     return $datas;
            // });


            $datas=[];
            $result = ScmStockLedger::query()           
            ->where('scm_warehouse_id', request()->scm_warehouse_id)            
            ->get()
            ->groupBy('scm_material_id'); 
     

            foreach($result as $items){                
                foreach($items as $key => $item){
                    $adas=[];
                    $adas['scmMaterial']= $items[$key]->scmMaterial;
                    $adas['scm_warehouse_id']= $items[$key]->scm_warehouse_id;
                    $adas['opening_stock']= $items->where('date', '<', request()->from_date)?->sum('quantity') ?? ($items->where('date', '=', request()->from_date)->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity ?? 0);
                    
                    
                    if($adas['opening_stock'] >= 0){
                        $adas['opening_stock']= $adas['opening_stock'];
                    }else{
                        $adas['opening_stock']=0;
                    }

                    $adas['received']= $items->whereNull('parent_id')->whereBetween('date', [request()->from_date, request()->end_date])?->sum('quantity') - (empty($result['opening_stock'])? $items->where('date', '=', request()->from_date)->where('stockable_type', 'Modules\SupplyChain\Entities\ScmOpeningStock')->first()?->quantity : 0);

                    // dump($items->whereNull('parent_id'));

                    $adas['consumed']= $items->whereNotNull('parent_id')->whereBetween('date', [request()->from_date, request()->end_date])?->sum('quantity') * -1;

                    $adas['closing_stock']= $adas['opening_stock'] + $adas['received'] - $adas['consumed'];

                    $datas[] = $adas;
                    break;
                }               

            }


            return response()->success('data', $datas, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }


}
