<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LighterVoyageReportExport implements FromView
{
    public $voyageReportData;
    public function __construct($voyageReportData) {
        $this->voyageReportData = $voyageReportData;
    }

    public function view(): View {
        return view('operations::reports.excel-lighter-voyage-report',with(['data' => $this->voyageReportData]));
    }
}
