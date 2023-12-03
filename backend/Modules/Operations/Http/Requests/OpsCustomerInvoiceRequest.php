<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsCustomerInvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_customer_id'       => ['required', 'numeric', 'max:50'],
            'sub_total'             => ['required', 'numeric'],
            'discount'              => ['required', 'string'],
            'grand_total'           => ['required', 'numeric'],
            'opsCustomerInvoiceLines.*.amount'         => ['nullable', 'numeric', 'max:255'],
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
            'ops_customer_id.required' => 'Customer is required',            
            'sub_total.required' => 'Sub total is required',
            'discount.required' => 'Discount is required',
            'grand_total.required' => 'Grand total is required',
            'opsCustomerInvoiceLines.*.amount.max' => 'Amount may not be greater than :max characters for row is :position.',
           
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
