<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmOpeningStockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'scm_warehouse_id' => 'required|exists:scm_warehouses,id|integer',
            'scmOpeningStockLines.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmOpeningStockLines.*.unit' => 'required|max:255|exists:scm_units,name|string',
            'scmOpeningStockLines.*.rate' => 'required|numeric',
            'scmOpeningStockLines.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'scmOpeningStockLines.*.quantity.min' => 'In row no :position you have given :input but minimum amount is :min',
            'scmOpeningStockLines.*.quantity.required' => 'In row no :position quantity is required',
            'scmOpeningStockLines.*.scm_material_id.required' => 'In row no :position material is required',
            'scmOpeningStockLines.*.rate.required' => 'In row no :position rate is required',
            'scmOpeningStockLines.*.unit.required' => 'In row no :position unit is required',
            'scmOpeningStockLines.*.scm_material_id.exists' => 'In row no :position material is not found',
            'scmOpeningStockLines.*.unit.exists' => 'In row no :position unit is not found',
            'scmOpeningStockLines.*.rate.numeric' => 'In row no :position rate must be a number',
            'scmOpeningStockLines.*.quantity.numeric' => 'In row no :position quantity must be a number',
            'scmOpeningStockLines.*.scm_material_id.integer' => 'In row no :position material must be an integer',
            'scmOpeningStockLines.*.unit.string' => 'In row no :position unit must be an string',
        ];
    }


    // [:attribute] [:index] [:rule] [:size] [:values] [:custom] [:extra] [:attribute] [:rule] [:parameters] [:size] [:values] [:custom] [:extra] [:value] [:max] [:min]',
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
