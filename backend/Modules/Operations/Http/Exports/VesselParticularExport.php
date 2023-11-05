<?php

namespace Modules\Operations\Http\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Operations\Entities\OpsVesselParticular;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VesselParticularExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $vesselParticulars;
    public function __construct($vesselParticulars) {
        // dd($vesselParticulars);
        $this->vesselParticulars = $vesselParticulars;
    }

    public function view(): View {
        return view('vessel-particulars.vessel_particulars')->with([
            'vesselParticulars' => $this->vesselParticulars,
        ]);
    }

}
