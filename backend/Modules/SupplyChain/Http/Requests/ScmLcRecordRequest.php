<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmLcRecordRequest extends FormRequest
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
        // 'amount','particular'
        return [

            'lc_no' => 'required|max:255',
            'lc_date' => 'required|date',
            'expire_date' => 'required|date',
            'weight' => 'required|string',
            'no_of_packet' => 'sometimes|nullable|numeric',
            'scm_po_id' => 'required|integer|exists:scm_pos,id',
            'invoice_value' => 'numeric',
            'assessment_value' => 'required|numeric',
            // 'issue_bank_id' => 'required|integer|exists:acc_banks,id',
            'issue_bank_name' => 'required|max:255',
            // 'advising_bank_id' => 'required|integer|exists:acc_banks,id',
            'advising_bank_name' => 'max:255',
            // 'discounting_bank_id' => 'required|integer|exists:acc_banks,id',
            'discounting_bank_name' => 'max:255',
            // 'beneficiary_bank_id' => 'required|integer|exists:acc_banks,id',
            'beneficiary_bank_name' => 'required|max:255',
            'scm_vendor_id' => 'required|integer|exists:scm_vendors,id',
            'lc_type' => 'required',
            // 'acc_bank_id' => 'required|integer|exists:acc_banks,id',
            'bank_name' => 'max:255',
            'cfr_value' => 'required|numeric',
            'lc_margin' => 'numeric|required',
            'total_cost' => 'numeric|required',
            'document_value' => 'numeric|required',
            'exchange_rate' => 'required|numeric',
            'market_rate' => 'numeric|required',
            'attachment' => 'nullable|mimes:xlsx,pdf,jpg,png,jpeg,doc,docx',
            'scmLcRecordLines.*.amount' => 'required|numeric',
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

            'assessment_value.required' => 'Assessment value is required',
            'assessment_value.numeric' => 'Assessment value must be a number',

            'issue_bank_name.required' => 'Issue bank name is required',
            'issue_bank_name.max' => 'Issue bank name must be less than 255 characters',

            'beneficiary_bank_name.required' => 'Beneficiary bank name is required',
            'beneficiary_bank_name.max' => 'Beneficiary bank name must be less than 255 characters',

            'remarks.max' => 'Remarks must be less than 255 characters',

            'purchase_center.required' => 'Purchase center is required',
            'purchase_center.max' => 'Purchase center must be less than 255 characters',

            'is_critical.required' => 'Criticality is required',
            'is_critical.integer' => 'Criticality must be an integer',

            'approved_date.required' => 'Approved date is required',
            'approved_date.date' => 'Approved date must be a date',

            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a number',

            'particular.required' => 'Particular is required',

            'scmLcRecordLines.*.amount.required' => 'Amount is required',
            'scmLcRecordLines.*.amount.numeric' => 'Amount must be a number',
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
