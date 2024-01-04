<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVoyage;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVesselBunker;

class OpsVoyageReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
 
    // public function voyageReport(Request $request)
    // {
    //     try {
            
    //         $voyages = OpsVoyage::with('opsVessel.opsVesselBunkers.opsBunkers.scmMaterial','opsVesselBunkers.opsBunkers.scmMaterial','opsCargoType','opsVoyageSectors.opsContractTariff.opsCargoTariff.opsCargoTariffLines','opsContractTariffs.opsCargoTariff.opsCargoTariffLines','opsVoyageExpenditureEntries.opsExpenseHead')
    //         ->whereHas('opsVoyageExpenditureEntries.opsExpenseHead', function ($query) {
    //             $query->where('is_visible_in_voyage_report',1)->groupBy('name');
    //         })
    //         ->when(isset(request()->from_date) && isset(request()->to_date), function($query) {
    //             $query->whereBetween('sail_date', [request()->from_date, request()->to_date]);
    //         })
    //         ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
    //             $query->where('business_unit', request()->business_unit);
    //         })
    //         ->get();
            
    //         $bunkerMaterialTitle=[];
    //         foreach($voyages as $voyage){                
    //             foreach($voyage->opsVesselBunkers as $vesselBunker){
    //                 foreach($vesselBunker->opsBunkers as $bunker){
    //                     $bunkerMaterialTitle[]=$bunker->scmMaterial->name;
    //                 }
    //             }
    //         }



    //         $opsVesselBunkerTitle = $voyages->flatMap(function ($voyage){
    //             return $voyage->opsVesselBunkers?->flatMap(function ($opsVesselBunker){
    //                 // $opsVesselBunker->opsBunkers->sum('quantity');
                    
    //                 return collect($opsVesselBunker?->opsBunkers->whereNotNull('particular'))->map(function ($bunker){                                             
    //                         return [
    //                             'particular' => $bunker['particular'],
    //                         ];
    //                     // dd($bunker['particular']);
    //                 });
    //             });
    //         })->unique('particular');

    //         $opsCargoTitle = $voyages->flatMap(function ($voyage) {
    //             return $voyage->opsContractTariffs->flatMap(function ($opsContractTariff) use ($voyage) {
    //                 return collect($opsContractTariff->opsCargoTariff->opsCargoTariffLines)->map(function ($tariffLine) use ($opsContractTariff, $voyage) {
                        
    //                     return [
    //                         'particular' => $tariffLine['particular'],                            
    //                     ];
    //                 });
    //             });
    //         })->unique('particular');

    //         $opsExpenditureHeadTitle = $voyages->flatMap(function ($voyage) {
    //             return $voyage->opsVoyageExpenditureEntries->map(function ($opsExpenditure) use ($voyage) {
    //                 $opsExpenditure= [
    //                     'name' => $opsExpenditure->opsExpenseHead['name'],                            
    //                 ];
    //                 return $opsExpenditure;
    //             });
    //         })->unique('name');

    //         $voyages->map(function ($voyage) use($bunkerMaterialTitle){
    //             $voyage->opsVoyageSectors->map(function ($sector) {
    //                 $sector['quantity'] = $this->chooseQuantity($sector);
    //                 return $sector;
    //             });

    //             $voyage['opsContractTariffs']->map(function ($tariff) {
    //                 $tariff->opsCargoTariff?->opsCargoTariffLines
    //                 ->whereNotNull($tariff?->tariff_month)
    //                 ->map(function ($item) use ($tariff){
    //                     $tariff[$item['particular']] = $item[$tariff?->tariff_month];
    //                     return $tariff;
    //                 });
    //             });

    //             $voyage['opsVesselBunkers']->map(function($vessel_bunker){
    //                 $vessel_bunker['sub_total']= $vessel_bunker->opsBunkers->sum('quantity');                    
    //                 return $vessel_bunker;
    //             });

    //             $voyage['stock_in_total']= $voyage->opsVesselBunkers->where('type','Stock In')->sum('sub_total');
    //             $voyage['stock_out_total']= $voyage->opsVesselBunkers->where('type','Stock Out')->sum('sub_total');
    //             return $voyage;
    //         });

            


    //         $data= [
    //             'voyages'=> $voyages,
    //             'opsContractTitle'=> $opsCargoTitle,
    //             'opsExpenditureHeadTitle'=> $opsExpenditureHeadTitle,
    //             'opsVesselBunkerTitle'=> $opsVesselBunkerTitle,
    //             'bunkerMaterialTitle'=> array_unique($bunkerMaterialTitle),
    //             'companyName' => 'TOGGI SHIPPING & LOGISTIC',
    //         ];
    //         // return view('operations::reports.voyage',compact('data'));
    //         return response()->success('Data retrieved successfully.', $data, 200);
    //     }
    //     catch (QueryException $e)
    //     {
    //         return response()->error($e->getMessage(), 500);
    //     }



    // }

    public function voyageReport(Request $request)
    {
        try {
            
            $vesselBunkers= OpsVesselBunker::with(['opsVessel','opsBunkers.scmMaterial','opsVoyage.opsCargoType','opsVoyage.opsVoyageSectors.opsContractTariff.opsCargoTariff.opsCargoTariffLines','opsVoyage.opsContractTariffs.opsCargoTariff.opsCargoTariffLines','opsVoyage.opsVoyageExpenditureEntries.opsExpenseHead'])
            ->when(isset(request()->from_date) && isset(request()->to_date), function($query) {
                $query->whereBetween('date', [request()->from_date, request()->to_date]);
            })
            ->get();

            $bunkerMaterialTitle=[];
            foreach($vesselBunkers as $vesselBunker){                
                foreach($vesselBunker->opsBunkers as $bunker){
                    $bunkerMaterialTitle[]=$bunker->scmMaterial->name;
                }
            }

            $opsVesselBunkerTitle = $vesselBunkers->flatMap(function ($vesselBunker){                    
                return collect($vesselBunker?->opsBunkers->whereNotNull('particular'))->map(function ($bunker){                                             
                        return [
                            'particular' => $bunker['particular'],
                        ];
                });
            })->unique('particular');

            $opsCargoTitle = $vesselBunkers->flatMap(function ($vesselBunker) {
                return $vesselBunker?->opsVoyage?->opsContractTariffs->flatMap(function ($opsContractTariff) {
                    return collect($opsContractTariff->opsCargoTariff->opsCargoTariffLines)->map(function ($tariffLine){
                        
                        
                        return [
                            'particular' => $tariffLine['particular'],                            
                        ];
                    });
                });
            })->unique('particular');


            

            $opsExpenditureHeadTitle = $vesselBunkers->flatMap(function ($vesselBunker) {
                return $vesselBunker?->opsVoyage?->opsVoyageExpenditureEntries->map(function ($opsExpenditure){
                    $opsExpenditure= [
                        'name' => $opsExpenditure->opsExpenseHead['name'],                            
                    ];
                    return $opsExpenditure;
                });
            })->unique('name');

            $vesselBunkers->map(function ($vesselBunker) use($bunkerMaterialTitle){
                $vesselBunker?->opsVoyage?->opsVoyageSectors->map(function ($sector) {
                    $sector['quantity'] = $this->chooseQuantity($sector);
                    return $sector;
                });

                $vesselBunker?->opsVoyage?->opsContractTariffs->map(function ($tariff) {
                    $tariff->opsCargoTariff?->opsCargoTariffLines
                    ->whereNotNull($tariff?->tariff_month)
                    ->map(function ($item) use ($tariff){
                        $tariff[$item['particular']] = $item[$tariff?->tariff_month];
                        return $tariff;
                    });
                });

                $vesselBunker?->opsVoyage?->opsVesselBunkers->map(function($vessel_bunker){
                    $vessel_bunker['sub_total']= $vessel_bunker->opsBunkers->sum('quantity');                    
                    return $vessel_bunker;
                });

                $vesselBunker['stock_in_total']= $vesselBunker?->opsVoyage?->opsVesselBunkers->where('type','Stock In')->sum('sub_total');
                $vesselBunker['stock_out_total']= $vesselBunker?->opsVoyage?->opsVesselBunkers->where('type','Stock Out')->sum('sub_total');
                return $vesselBunker;
            });


            $data= [
                'vesselBunkers'=> $vesselBunkers,
                'opsContractTitle'=> $opsCargoTitle,
                'opsExpenditureHeadTitle'=> $opsExpenditureHeadTitle,
                'opsVesselBunkerTitle'=> $opsVesselBunkerTitle,
                'bunkerMaterialTitle'=> array_unique($bunkerMaterialTitle),
                'companyName' => 'TOGGI SHIPPING & LOGISTIC',
            ];
            return view('operations::reports.voyage',compact('data'));
            return response()->success('Data retrieved successfully.', $data, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }



    }


    public function chooseQuantity($item) {
        $cargo_quantity = 0;
            if ($item->final_received_qty > 0) {
                $cargo_quantity = $item->final_received_qty;
            } elseif ($item->final_survey_qty > 0) {
                $cargo_quantity = $item->final_survey_qty;
            } elseif ($item->boat_note_qty > 0) {
                $cargo_quantity = $item->boat_note_qty;
            } elseif ($item->initial_survey_qty > 0) {
                $cargo_quantity = $item->initial_survey_qty;
            }

            return $cargo_quantity;
    }
    

}
