<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmSrRequest extends FormRequest
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
            'scm_warehouse_id' => 'required|integer|exists:scm_warehouses,id',
            // 'acc_cost_center_id' => 'required|integer|exists:acc_cost_centers,id',
            'department_id' => 'required|integer',
            'date' => 'required|date',
            'remarks' => 'max:300',
            // 'business_unit' => 'required|max:255',
            // 'created_by' => 'required|integer|exists:users,id',
            'scmSrLines.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmSrLines.*.unit' => 'required|max:255|exists:scm_units,name|string',
            'scmSrLines.*.specification' => 'max:255',
            'scmSrLines.*.quantity' => 'required|numeric',
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
            'scm_warehouse_id.required' => 'Warehouse is required',
            'scm_warehouse_id.integer' => 'Warehouse must be an integer',
            'scm_warehouse_id.exists' => 'Warehouse does not exist',
            // 'acc_cost_center_id.required' => 'Cost center is required',
            // 'acc_cost_center_id.integer' => 'Cost center must be an integer',
            // 'acc_cost_center_id.exists' => 'Cost center does not exist',
            'department_id.required' => 'Department is required',
            'department_id.integer' => 'Department must be an integer',

            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',

            'scmSrLines.*.scm_material_id.required' => 'In row no :position Material is required',
            'scmSrLines.*.scm_material_id.integer' => 'In row no :position Material must be an integer',
            'scmSrLines.*.scm_material_id.exists' => 'In row no :position Material does not exist',

            'scmSrLines.*.unit.required' => 'In row no :position Unit is required',
            'scmSrLines.*.unit.max' => 'In row no :position Unit must not be greater than 255 characters',
            'scmSrLines.*.unit.exists' => 'In row no :position Unit does not exist',

            'scmSrLines.*.specification.max' => 'In row no :position Specification must not be greater than 255 characters',

            'scmSrLines.*.quantity.required' => 'In row no :position Quantity is required',
            'scmSrLines.*.quantity.numeric' => 'In row no :position Quantity must be numeric',            
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
