<?php

namespace App\Imports;

use App\Models\Unit;
use App\Models\Account;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\SupplyChain\Entities\ScmMaterial;

class ScmMaterialsImport implements ToCollection, WithValidation, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $invalid = [];
    public $uniqueRows = [];


    public function collection(Collection $rows)
    {
        $uniqueRow = [];
        foreach ($rows as $key => $row) {
            // return response()->json($key, 422);

            if (array_key_exists($row['material_name'], $uniqueRow)) {
                $errors = array("0" => "Material Name should be unique");
                array_push($this->invalid, $errors);
                return;
            }

            $material = ScmMaterial::where('name', $row['material_name'])->first();
            if (!$material) {
                $errors = array("0" => "Material missing in $key no column.");
                array_push($this->invalid, $errors);
                return;
            }

            $uniqueRow[$row['material_name']] = true;
        }

        foreach ($rows as $row) {
            $material_id = ScmMaterial::where('name', $row['material_name'])->first();

            $items = [
                'scm_material_id' => $material_id->id,
                'unit' => $row['unit'],
                'brand' => $row['brand'],
                'model' => $row['model'],
                'specification' => $row['specification'],
                'origin' => $row['origin'],
                'drawing_no' => $row['drawing_nofor_spare'],
                'quantity' => $row['quantity'],
                'required_date' => $row['required_date'],
            ];

            array_push($this->uniqueRows, $items);
        }
    }


    public function rules(): array
    {
        return [
            '*.material_name' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'material_name.required' => 'Material name is required',
            'material_name.string' => 'Material name must be a string',
        ];
    }
}
