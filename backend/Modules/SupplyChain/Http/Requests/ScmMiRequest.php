<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmMiRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data =  request('data');
        $dataArray = json_decode($data, true);

        // $mergeData = array_merge($dataArray, ['attachment' => request('attachment'), 'excel' => request('excel')]);

        $this->replace($dataArray);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'business_unit' => 'required',
            'scm_mmr_id' => 'required|exists:scm_mmrs,id',
            'scm_mo_id' => 'required|exists:scm_mos,id',
            'from_warehouse_id' => 'required|exists:scm_warehouses,id|integer',
            'to_warehouse_id' => 'required|exists:scm_warehouses,id|integer',
            'date' => 'required|date',
            'scmMiLins.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmMiLins.*.quantity' => 'required|numeric',
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
            'business_unit.required' => 'Business unit is required',

            'scm_mmr_id.required' => 'MMR is required',
            'scm_mmr_id.exists' => 'MMR is not valid',

            'scm_mo_id.required' => 'MO is required',
            'scm_mo_id.exists' => 'MO is not valid',

            'from_warehouse_id.required' => 'From warehouse is required',
            'from_warehouse_id.exists' => 'From warehouse is not valid',
            'from_warehouse_id.integer' => 'From warehouse is not valid',

            'to_warehouse_id.required' => 'To warehouse is required',
            'to_warehouse_id.exists' => 'To warehouse is not valid',
            'to_warehouse_id.integer' => 'To warehouse is not valid',

            'date.required' => 'Date is required',
            'date.date' => 'Date is not valid',

            'scmMiLins.*.scm_material_id.required' => 'Material is required',
            'scmMiLins.*.scm_material_id.exists' => 'Material is not valid',
            'scmMiLins.*.scm_material_id.integer' => 'Material is not valid',

            'scmMiLins.*.quantity.required' => 'Quantity is required',
            'scmMiLins.*.quantity.numeric' => 'Quantity is not valid',
        ];
    }

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
