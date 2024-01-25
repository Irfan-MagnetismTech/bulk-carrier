<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmWoRequest extends FormRequest
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
            'scm_vendor_id' => 'required|integer|exists:scm_vendors,id',
            'acc_cost_center_id' => 'required|integer|exists:acc_cost_centers,id',
            'currency' => 'required|max:255',
            'purchase_center' => 'nullable|max:255',
            // 'exchange_rate' => 'required|numeric',
            'usd_to_bdt' => 'required_if:currency,!=,BDT|numeric',
            'foreign_to_usd' => 'required_if:currency,!=,BDT,USD|numeric',
            'total_price' => 'required|numeric',
            'remarks' => 'nullable|max:255',
            'date' => 'required|date',
            'scmWoLine.*.scmWoItems.*.scm_service_id' => 'required|exists:scm_services,id|integer',
            'scmWoLine.*.scmWoItems.*.required_date' => 'required|date',
            'scmWoLine.*.scmWoItems.*.quantity' => 'required|numeric',
            'scmWoLine.*.scmWoItems.*.rate' => 'required|numeric',
            'scmWoLine.*.scmWoItems.*.description' => 'nullable|max:255',
            'scmWoTerms.*.description' => 'nullable|max:255',
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

            'scm_vendor_id.required' => 'Vendor is required',
            'scm_vendor_id.integer' => 'Vendor must be an integer',
            'scm_vendor_id.exists' => 'Vendor is not found',

            'acc_cost_center_id.required' => 'Cost center is required',
            'acc_cost_center_id.integer' => 'Cost center must be an integer',
            'acc_cost_center_id.exists' => 'Cost center is not found',

            'date.required' => 'Date is required',
            'date.date' => 'Date must be a date',
            
            'total_price.required' => 'Date is required',
            
            'remarks.max' => 'Remarks must be less than 255 characters',

            // 'purchase_center.required' => 'Purchase center is required',
            'purchase_center.max' => 'Purchase center must be less than 255 characters',

            'scmWoLine.*.scmWoItems.*.scm_material_id.required' => 'In row no :position Material is required',
            'scmWoLine.*.scmWoItems.*.scm_material_id.integer' => 'In row no :position Material must be an integer',
            'scmWoLine.*.scmWoItems.*.scm_material_id.exists' => 'In row no :position Material is not found',

            'scmWoLine.*.scmWoItems.*.unit.required' => 'In row no :position Unit is required',
            'scmWoLine.*.scmWoItems.*.unit.max' => 'In row no :position Unit must be less than 255 characters',
            
            'scmWoLine.*.scmWoItems.*.quantity.required' => 'In row no :position Quantity is required',
            'scmWoLine.*.scmWoItems.*.quantity.numeric' => 'In row no :position Quantity must be a number',
            
            'scmWoLine.*.scmWoItems.*.required_date.required' => 'In row no :position Required date is required',
            'scmWoLine.*.scmWoItems.*.required_date.date' => 'In row no :position Required date must be a date',
           
            'scmWoLine.*.scmWoItems.*.description.max' => 'In row no :position Description must be less than 255 characters',
            
            'scmWoTerms*.description.max' => 'Term Description must be less than 255 characters',
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
