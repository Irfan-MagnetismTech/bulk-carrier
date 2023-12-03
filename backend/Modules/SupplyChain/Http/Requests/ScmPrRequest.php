<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmPrRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data =  request('data');
        $dataArray = json_decode($data, true);
        $imgData = is_object(request('attachment')) ? request('attachment') : null;
        $mergeData = array_merge($dataArray, ['attachment' => $imgData, 'excel' => request('excel')]);

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
            // 'acc_cost_center_id' => 'required|integer|exists:acc_cost_centers,id',
            'attachment' => 'nullable|mimes:xlsx,pdf,jpg,png,jpeg,doc,docx',
            'raised_date' => 'required|date',
            'remarks' => 'max:255',
            'purchase_center' => 'required|max:255',
            'is_critical' => 'required|integer',
            'approved_date' => 'required|date',
            'scmPrLines.*.scm_material_id' => 'exclude_if:entry_type,1|required|exists:scm_materials,id|integer',
            'scmPrLines.*.unit' => 'exclude_if:entry_type,1|required|max:255|exists:scm_units,name|string',
            'scmPrLines.*.brand' => 'exclude_if:entry_type,1|max:255',
            'scmPrLines.*.model' => 'exclude_if:entry_type,1|max:255',
            'scmPrLines.*.country_name' => 'exclude_if:entry_type,1|max:255',
            'scmPrLines.*.drawing_no' => 'exclude_if:entry_type,1|max:255',
            'scmPrLines.*.part_no' => 'exclude_if:entry_type,1|max:255',
            'scmPrLines.*.specification' => 'exclude_if:entry_type,1|max:255',
            'scmPrLines.*.quantity' => 'exclude_if:entry_type,1|required|numeric|min:1',
            'scmPrLines.*.required_date' => 'exclude_if:entry_type,1|required|date',
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

            'raised_date.required' => 'Raised date is required',
            'raised_date.date' => 'Raised date must be a date',

            'remarks.max' => 'Remarks must be less than 255 characters',

            'purchase_center.required' => 'Purchase center is required',
            'purchase_center.max' => 'Purchase center must be less than 255 characters',

            'is_critical.required' => 'Criticality is required',
            'is_critical.integer' => 'Criticality must be an integer',

            'approved_date.required' => 'Approved date is required',
            'approved_date.date' => 'Approved date must be a date',

            'scmPrLines.*.scm_material_id.required' => 'In row no :position Material is required',
            'scmPrLines.*.scm_material_id.integer' => 'In row no :position Material must be an integer',
            'scmPrLines.*.scm_material_id.exists' => 'In row no :position Material is not found',

            'scmPrLines.*.unit.required' => 'In row no :position Unit is required',
            'scmPrLines.*.unit.max' => 'In row no :position Unit must be less than 255 characters',

            'scmPrLines.*.quantity.required' => 'In row no :position Quantity is required',
            'scmPrLines.*.quantity.numeric' => 'In row no :position Quantity must be a number',
            'scmPrLines.*.quantity.min' => 'In row no :position Quantity must be greater than 0',

            'scmPrLines.*.required_date.required' => 'In row no :position Required date is required',
            'scmPrLines.*.required_date.date' => 'In row no :position Required date must be a date',
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
