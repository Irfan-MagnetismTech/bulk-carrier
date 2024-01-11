<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmWrRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data =  request('info');
        $dataArray = json_decode($data, true);
        $mergeData = array_merge($dataArray, ['attachment' => request('attachment')]);
        $this->replace($mergeData);
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
            'acc_cost_center_id' => 'required|integer',
            'raised_date' => 'required|date',
            'approved_date' => 'required|date',
            'attachment' => 'nullable|mimes:xlsx,pdf,jpg,png,jpeg,doc,docx',
            'remarks' => 'max:500',

            'scmWrLines.*.scm_material_id' => 'exclude_if:entry_type,1|required|exists:scm_materials,id|integer',
            'scmWrLines.*.quantity' => 'exclude_if:entry_type,1|required|numeric|min:1',
            'scmWrLines.*.required_date' => 'exclude_if:entry_type,1|required|date',
            'scmWrLines.*.description' => 'max:500',
            'scmWrLines.*.remarks' => 'max:500',
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
            'acc_cost_center_id.required' => 'Warehouse is required',
            'acc_cost_center_id.integer' => 'Warehouse must be an integer',
            'raised_date.required' => 'Approved date is required',
            'raised_date.date' => 'Approved date must be a date',
            'approved_date.required' => 'Approved date is required',
            'approved_date.date' => 'Approved date must be a date',
            'attachment.required' => 'Attachment is required',
            'attachment.mimes' => 'Attachment must be an xlsx,pdf,jpg,png,jpeg,doc,docx',
            'remarks.max' => 'Remarks must be less than 500 characters',

            
            'scmWrLines.*.scm_material_id.required' => 'In row no :position Material is required',
            'scmWrLines.*.scm_material_id.integer' => 'In row no :position Material must be an integer',
            'scmWrLines.*.scm_material_id.exists' => 'In row no :position Material is not found',
            
            'scmWrLines.*.description.max' => 'In row no :position Description must be less than 500 characters',
            'scmWrLines.*.remarks.max' => 'In row no :position Remarks must be less than 500 characters',
            
            'scmWrLines.*.quantity.required' => 'In row no :position Quantity is required',
            'scmWrLines.*.quantity.numeric' => 'In row no :position Quantity must be a number',
            'scmWrLines.*.quantity.min' => 'In row no :position Quantity must be greater than 0',

            'scmWrLines.*.required_date.required' => 'In row no :position Required date is required',
            'scmWrLines.*.required_date.date' => 'In row no :position Required date must be a date',

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
