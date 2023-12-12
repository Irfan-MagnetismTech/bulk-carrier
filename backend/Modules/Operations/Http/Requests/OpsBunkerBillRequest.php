<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsBunkerBillRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        // dd($dataArray);
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
        $mergeData = array_merge($mergeData , ['smr_file_path' => request('smr_file_path')]);
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
            'date'              => ['required', 'date'],
            'scm_vendor_id'     => ['required', 'numeric', 'max:20'],
            'vendor_bill_no'    => ['required', 'string', 'max:255'],
            'sub_total'         => ['required', 'numeric'],
            'discount'          => ['nullable', 'numeric'],
            'grand_total'       => ['required', 'numeric'],
            // 'attachment'        => ['nullable', 'string'],
            // 'smr_file_path'     => ['nullable', 'string'],
            'remarks'           => ['nullable', 'string', 'max:2550'],
            'opsBunkerBillLines.*.rate' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.exchange_rate_bdt' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.exchange_rate_usd' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.amount' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.amount_bdt' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.amount_usd' =>  ['nullable', 'numeric'],
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
            'date.required' => 'Date is required.',
            'scm_vendor_id.required' => 'Vendor is required.',
            'vendor_bill_no.required' => 'Vendor Bill No. is required.',
            'discount.numeric' => 'Discount must be numeric.',
            'sub_total.numeric' => 'Sub total must be numeric.',
            'sub_total.required' => 'Sub total is required.',
            'grand_total.numeric' => 'Grand total must be numeric.',
            'grand_total.required' => 'Grand total is required.',
            'opsBunkerBillLines.*.rate.numeric' => 'Rate must be numeric for row is :position.',
            'opsBunkerBillLines.*.exchange_rate_bdt.numeric' => 'Exchange Rate(BDT) must be numeric for row is :position.',
            'opsBunkerBillLines.*.exchange_rate_usd.numeric' => 'Exchange Rate(USD) must be numeric for row is :position.',
            'opsBunkerBillLines.*.amount.numeric' => 'Amount must be numeric for row is :position.',
            'opsBunkerBillLines.*.amount_bdt.numeric' => 'Amount(BDT) must be numeric for row is :position.',
            'opsBunkerBillLines.*.amount_usd.numeric' => 'Amount(USD) must be numeric for row is :position.',
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
