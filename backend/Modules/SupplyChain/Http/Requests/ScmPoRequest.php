<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmPoRequest extends FormRequest
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
            'acc_cost_center_id' => 'required|integer|exists:acc_cost_centers,id',
            'date' => 'required|date',
            'remarks' => 'max:255',
            'purchase_center' => 'required|max:255',
            'is_critical' => 'required|integer',
            'approved_date' => 'required|date',
            'scmPoLines.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmPoLines.*.unit' => 'required|max:255|exists:scm_units,name|string',
            'scmPoLines.*.brand' => 'max:255',
            'scmPoLines.*.model' => 'max:255',
            'scmPoLines.*.country_name' => 'max:255',
            'scmPoLines.*.drawing_no' => 'max:255',
            'scmPoLines.*.part_no' => 'max:255',
            'scmPoLines.*.specification' => 'max:255',
            'scmPoLines.*.quantity' => 'required|numeric',
            'scmPoLines.*.required_date' => 'required|date',
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
            'scm_warehouse_id.exists' => 'Warehouse is not found',

            'acc_cost_center_id.required' => 'Cost center is required',
            'acc_cost_center_id.integer' => 'Cost center must be an integer',
            'acc_cost_center_id.exists' => 'Cost center is not found',

            'attachment.required' => 'Attachment is required',
            'attachment.mimes' => 'Attachment must be an xlsx,pdf,jpg,png,jpeg,doc,docx',

            'date.required' => 'Date is required',
            'date.date' => 'Date must be a date',

            'remarks.max' => 'Remarks must be less than 255 characters',

            'purchase_center.required' => 'Purchase center is required',
            'purchase_center.max' => 'Purchase center must be less than 255 characters',

            'is_critical.required' => 'Criticality is required',
            'is_critical.integer' => 'Criticality must be an integer',

            'approved_date.required' => 'Approved date is required',
            'approved_date.date' => 'Approved date must be a date',

            'scmPoLines.*.scm_material_id.required' => 'In row no :position Material is required',
            'scmPoLines.*.scm_material_id.integer' => 'In row no :position Material must be an integer',
            'scmPoLines.*.scm_material_id.exists' => 'In row no :position Material is not found',

            'scmPoLines.*.unit.required' => 'In row no :position Unit is required',
            'scmPoLines.*.unit.max' => 'In row no :position Unit must be less than 255 characters',

            'scmPoLines.*.quantity.required' => 'In row no :position Quantity is required',
            'scmPoLines.*.quantity.numeric' => 'In row no :position Quantity must be a number',

            'scmPoLines.*.required_date.required' => 'In row no :position Required date is required',
            'scmPoLines.*.required_date.date' => 'In row no :position Required date must be a date',
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
