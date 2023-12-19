<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsCustomerInvoiceRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        // dd(json_decode($data, true));
        $this->replace(json_decode($data, true));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // dd($this);
        return [
            'ops_customer_id'       => ['required', 'numeric', 'max:50'],
            'sub_total_amount'             => ['required', 'numeric'],
            'discounted_amount'              => ['nullable', 'numeric'],
            'grand_total'           => ['required', 'numeric'],
            'date'           => ['required', 'date'],
            'opsCustomerInvoiceLines.*.amount' => ['nullable', 'numeric'],
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
            'ops_customer_id.required' => 'Customer is required.',
            'sub_total.required' => 'Sub total is required.',
            'discounted_amount.required' => 'Discount is required.',
            'discounted_amount.numeric' => 'Discount must be numeric.',
            'grand_total.required' => 'Grand total is required.',
            'date.required' => 'Date is required.',
            'opsCustomerInvoiceLines.*.amount.numeric' => 'Sectors amount must be numeric for row is :position.',

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
