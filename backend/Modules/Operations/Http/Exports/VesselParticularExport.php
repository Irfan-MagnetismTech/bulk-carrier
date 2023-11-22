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

class VesselParticularExport implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $vesselParticular;
    public function __construct($vesselParticular) {
        $this->vesselParticular = $vesselParticular;
    }

    public function view(): View {
        return view('operations::vessel-particulars.vessel_particular')->with([
            'vesselParticular' => $this->vesselParticular,            
            'companyName' => 'TOGGI SHIPPING & LOGISTIC',
        ]);
    }

    public function title(): string
    {
        return 'Vessel Particular';
    }

}
