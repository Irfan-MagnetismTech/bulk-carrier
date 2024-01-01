<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Modules\Operations\Entities\OpsVoyage;

class OpsVoyageReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function voyageReport(Request $request)
    {
        try {
            $voyages = OpsVoyage::with('opsVessel','opsCargoType','opsVoyageSectors.opsContractTariff.opsCargoTariff.opsCargoTariffLines','opsContractTariffs.opsCargoTariff.opsCargoTariffLines','opsVoyageExpenditureEntries.opsExpenseHead')
            ->whereHas('opsVoyageExpenditureEntries.opsExpenseHead', function ($query) {
                $query->where('is_visible_in_voyage_report',1)->groupBy('name');
            })
            ->when(isset(request()->from_date) && isset(request()->to_date), function($query) {
                $query->whereBetween('sail_date', [request()->from_date, request()->to_date]);
            })
            ->when(isset(request()->business_unit) && request()->business_unit != "ALL", function($query){
                $query->where('business_unit', request()->business_unit);
            })
            ->get();
            
            $opsCargoTitle = $voyages->flatMap(function ($voyage) {
                return $voyage->opsContractTariffs->flatMap(function ($opsContractTariff) use ($voyage) {
                    return collect($opsContractTariff->opsCargoTariff->opsCargoTariffLines)->map(function ($tariffLine) use ($opsContractTariff, $voyage) {
                        return [
                            'particular' => $tariffLine['particular'],                            
                        ];
                    });
                });
            })->unique('particular');

            $opsExpenditureHeadTitle = $voyages->flatMap(function ($voyage) {
                return $voyage->opsVoyageExpenditureEntries->map(function ($opsExpenditure) use ($voyage) {
                    $opsExpenditure= [
                        'name' => $opsExpenditure->opsExpenseHead['name'],                            
                    ];
                    return $opsExpenditure;
                });
            })->unique('name');


            $voyages->map(function ($voyage) {
                $voyage->opsVoyageSectors->map(function ($sector) {
                    $sector['quantity'] = $this->chooseQuantity($sector);
                    return $sector;
                });

                $voyage['opsContractTariffs']->map(function ($tariff) {
                    $tariff->opsCargoTariff?->opsCargoTariffLines
                    ->whereNotNull($tariff?->tariff_month)
                    ->map(function ($item) use ($tariff){
                        $tariff[$item['particular']] = $item[$tariff?->tariff_month];
                        return $tariff;
                    });
                });
                return $voyage;
            });

            


            $data= [
                'voyages'=>$voyages,
                'opsContractTitle'=>$opsCargoTitle,
                'opsExpenditureHeadTitle'=>$opsExpenditureHeadTitle,
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
