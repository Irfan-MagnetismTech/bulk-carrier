<?php

namespace Modules\Operations\Http\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Operations\Entities\OpsVesselParticular;

class BulkNoonReportExport implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $bulkNoonReport;
    public function __construct($bulkNoonReport) {
        $this->bulkNoonReport = $bulkNoonReport;
    }

    public function view(): View {
        return view('operations::bulk-noon-reports.bulk_noon_report')->with([
            'bulkNoonReport' => $this->bulkNoonReport,            
            'companyName' => 'TOGGI SHIPPING & LOGISTIC',
        ]);
    }

    public function title(): string
    {
        return 'Bulk Noon Report';
    }

}
