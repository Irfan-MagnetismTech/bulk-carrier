<?php

namespace Modules\Operations\Http\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SupplierBillExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $bunker_bill;
    public function __construct($bunker_bill) {
        $this->bunker_bill = $bunker_bill;
    }

    public function view(): View {
        return view('operations::supplier-bills.supplier_bill')->with([
            'bunker_bill' => $this->bunker_bill,
            'companyName' => 'TOGGI SHIPPING & LOGISTIC',
        ]);
    }

}
