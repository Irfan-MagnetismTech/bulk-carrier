<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmLcRecordRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data =  request('data');
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
        // 'amount','particular'
        return [

            'lc_no' => 'required|max:255',
            'lc_date' => 'required|date',
            'expire_date' => 'required|date',
            'weight' => 'required|string',
            'no_of_packet' => 'sometimes|nullable|numeric',
            'scm_po_id' => 'required|integer|exists:scm_pos,id',
            'invoice_value' => 'numeric',
            'assessment_value' => 'numeric',
            // 'issue_bank_id' => 'required|integer|exists:acc_banks,id',
            'issue_bank_name' => 'required|max:255',
            // 'advising_bank_id' => 'required|integer|exists:acc_banks,id',
            'advising_bank_name' => 'max:255',
            // 'discounting_bank_id' => 'required|integer|exists:acc_banks,id',
            'discounting_bank_name' => 'max:255',
            // 'beneficiary_bank_id' => 'required|integer|exists:acc_banks,id',
            'beneficiary_bank_name' => 'max:255',
            'scm_vendor_id' => 'required|integer|exists:scm_vendors,id',
            'scm_warehouse_id' => 'required|integer|exists:scm_warehouses,id',
            'lc_type' => 'required|integer',
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
            //
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
