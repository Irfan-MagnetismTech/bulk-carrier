<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmCsRequest extends FormRequest
{

    //preparefor validation
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
            'effective_date' => 'required|date|before_or_equal:expire_date',
            'expire_date' => 'required|date',
            'parchase_center' => 'nullable|string',
            'scm_warehouse_id' => 'nullable|exists:scm_warehouses,id|integer',
            'required_days' => 'required|integer',
            'special_instructions' => 'nullable|string|max:300',
            'scmCsMaterials.*.scm_pr_id' => 'required|exists:scm_prs,id|integer',
            'scmCsMaterials.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmCsMaterials.*.quantity' => 'required|numeric',
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

            'effective_date.required' => 'Effective Date is required',
            'effective_date.date' => 'Effective Date must be a date',
            'effective_date.before_or_equal' => 'Effective Date must be before or equal to Expire Date',

            'expire_date.required' => 'Expire Date is required',
            'expire_date.date' => 'Expire Date must be a date',

            'parchase_center.string' => 'Purchase Center must be a string',
            'parchase_center.nullable' => 'Purchase Center must be a string',

            'scm_warehouse_id.exists' => 'Warehouse is not valid',
            'scm_warehouse_id.integer' => 'Warehouse is not valid',
            'scm_warehouse_id.nullable' => 'Warehouse is not valid',

            'required_days.required' => 'Required Days is required',
            'required_days.integer' => 'Required Days must be an integer',

            'special_instructions.required' => 'Special Instructions is required',
            'special_instructions.string' => 'Special Instructions must be a string',

            'scmCsMaterials.*.scm_pr_id.required' => 'In row no :position PR is required',
            'scmCsMaterials.*.scm_pr_id.exists' => 'In row no :position PR is not valid',
            'scmCsMaterials.*.scm_pr_id.integer' => 'In row no :position PR is not valid',

            'scmCsMaterials.*.scm_material_id.required' => 'In row no :position Material is required',
            'scmCsMaterials.*.scm_material_id.exists' => 'In row no :position Material is not valid',
            'scmCsMaterials.*.scm_material_id.integer' => 'In row no :position Material is not valid',

            'scmCsMaterials.*.quantity.required' => 'In row no :position Quantity is required',
            'scmCsMaterials.*.quantity.numeric' => 'In row no :position Quantity must be a number',
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
