<?php

namespace Modules\Operations\Http\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VesselParticularExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $vesselParticular;
    public function __construct($vesselParticular) {
        // dd($vesselParticulars);
        $this->vesselParticular = $vesselParticular;
    }

    public function view(): View {
        return view('operations::vessel-particulars.vessel_particular')->with([
            'vesselParticular' => $this->vesselParticular,            
            'companyName' => 'TOGGI SHIPPING & LOGISTIC',
        ]);
    }

}
