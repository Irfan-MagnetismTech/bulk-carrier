<?php

namespace Modules\Operations\Http\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LighterNoonReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $lighter_noon_reports;
    public function __construct($lighter_noon_reports) {
        $this->lighter_noon_reports = $lighter_noon_reports;
    }

    public function view(): View {
        return view('operations::lighter-noon-reports.lighter_noon_report')->with([
            'lighter_noon_reports' => $this->lighter_noon_reports,
            'companyName' => 'TOGGI SHIPPING & LOGISTIC',
        ]);
    }

}
