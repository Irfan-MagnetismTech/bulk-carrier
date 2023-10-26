<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\SupplyChain\Entities\ScmMaterial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ScmMaterialsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Material Name',
            'Unit',
            'Brand',
            'Model',
            'Specification',
            'Origin',
            'Drawing No(For Spare)',
            'Quantity',
            'Required Date'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $scmMaterials = ScmMaterial::query()
            ->where('store_category', request()->store_category)
            ->get()
            ->map(function ($material) {
                return [
                    'material_name' => $material->name,
                    'unit' => $material->unit,
                    'brand' => null,
                    'model' => null,
                    'specification' => null,
                    'origin' => null,
                    'drawing_no' => null,
                    'qty' => null,
                    'required_date' => null,
                ];
            });

        return $scmMaterials;
    }
}
