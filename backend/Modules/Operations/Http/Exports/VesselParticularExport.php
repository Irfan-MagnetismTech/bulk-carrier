<?php

namespace Modules\Operations\Http\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Operations\Entities\OpsVesselParticular;

class VesselParticularExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'BASHUNDHARA EMPRESS',
            'OWNER',
            'MANAGER/OPERATOR',
            'CLASSIFICATION',
            'FLAG',
            'PORT OF REGISTRY',
            'GROSS TONNAGE// NET TONNAGE',
            'IMO NUMBER',
            'MMSI',
            'OFFICIAL NUMBER',
            'KEEL LAYING/LAUNCHING/DELIVERY',
            'LENGTH OVERALL',
            'LENGTH BETWEEN PERPENDICULARS',
            'BREADTH MOULDED',
            Depth Moulded
            Sumer LOad Draft, Extreme
            TPC
            Deck Crane
            Grabs
            MAX. HT. KEEL TO UPPER MAST
            MAIN ENGINE
            MAX. CONTINOUS OUTPUT, BHP
            NORMAL OUTPUT,(85% OF MCO) BHP
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $vesselParticulars = OpsVesselParticular::query()
            ->where('store_category', request()->store_category)
            ->get();
            // ->map(function ($material) {
            //     return [
            //         'material_name' => $material->name,
            //         'unit' => $material->unit,
            //         'brand' => null,
            //         'model' => null,
            //         'specification' => null,
            //         'origin' => null,
            //         'drawing_no' => null,
            //         'qty' => null,
            //         'required_date' => null,
            //     ];
            // });

        return $vesselParticulars;
    }
}
