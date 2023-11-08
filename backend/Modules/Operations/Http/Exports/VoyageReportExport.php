<?php

namespace Modules\Operations\Http\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VoyageReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $voyage_reports;
    public function __construct($voyage_reports) {
        $this->voyage_reports = $voyage_reports;
    }

    public function view(): View {
        return view('operations::voyage-reports.voyage_report')->with([
            'voyage_reports' => $this->voyage_reports,
            'companyName' => 'TOGGI SHIPPING & LOGISTIC',
        ]);
    }

}
