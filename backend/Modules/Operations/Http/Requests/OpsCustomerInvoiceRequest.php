<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
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
            'ops_customer_id'       => ['required','exists:ops_customers,id'],
            'sub_total_amount'             => ['required', 'numeric'],
            'discounted_amount'              => ['nullable', 'numeric'],
            'grand_total'           => ['required', 'numeric'],
            'date'           => ['required', 'date'],
            'opsCustomerInvoiceLines.*.amount' => ['nullable', 'numeric'],
            'opsCustomerInvoiceVoyages.*.ops_voyage_id' =>[ 'nullable','exists:ops_voyages,id','max:20',Rule::unique('ops_customer_invoice_voyages')->ignore($this->route('customer_invoice'), 'id')],
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
            'ops_customer_id.exists' => 'Customer is not valid.',
            'sub_total.required' => 'Sub total is required.',
            'discounted_amount.numeric' => 'Discount must be numeric.',
            'grand_total.required' => 'Grand total is required.',
            'date.required' => 'Date is required.',
            'opsCustomerInvoiceLines.*.amount.numeric' => 'Amount must be numeric. Invalid data in row :position.',
            'opsCustomerInvoiceVoyages.*.ops_voyage_id.unique' => 'One or more voyages is already invoiced. Invalid data in row :position.',
            'opsCustomerInvoiceVoyages.*.ops_voyage_id.exists' => 'Voyage is not valid in row :position.',

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