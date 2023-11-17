<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsCustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code'                  => ['required', 'string', 'max:20', Rule::unique('ops_customers')->ignore($this->route('customer'), 'id')],
            'legal_name'            => ['required', 'string', 'max:255'],
            'name'                  => ['required', 'string', 'max:255'],
            'postal_address'        => ['nullable', 'string', 'max:255'],
            'city'                  => ['nullable', 'string', 'max:255'],
            'post_code'             => ['nullable', 'string', 'max:255'],
            'country'               => ['required', 'string', 'max:255'],
            'tax_id'                => ['nullable', 'string', 'max:255'],
            'business_license_no'   => ['nullable', 'string', 'max:255'],
            'bin_gst_sst_type'      => ['nullable', 'string', 'max:255'],
            'bin_gst_sst_no'        => ['nullable', 'string', 'max:255'],
            // 'phone' => ['required', 'numeric', function ($attribute, $value, $fail) {
            //     if (strlen((string) $value) < 10) {
            //         $fail('Contact phone must be at least 10 characters.');
            //     }
            // }],
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'company_reg_no'        => ['nullable', 'string', 'max:255'],
            'email_general'         => ['nullable', 'email'],
            'email_agreement'       => ['nullable', 'email'],
            'email_invoice'         => ['nullable', 'email'],
            'business_unit'         => ['nullable', 'string', 'max:255'],
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
            'code.required' => 'Customer code is required',
            'code.unique' => 'Customer code is already taken',
            'code.max' => 'Customer code may not be greater than :max characters.',
            'legal_name.required' => 'Legal name is required',
            'legal_name.max' => 'Legal name may not be greater than :max characters.',
            'name.required' => 'Customer name is required',
            'name.max' => 'Customer name may not be greater than :max characters.',
            'postal_address.max' => 'Postal address may not be greater than :max characters.',
            'post_code.max' => 'Post code may not be greater than :max characters.',
            'country.required' => 'Country is required',
            'country.max' => 'Country may not be greater than :max characters.',
            'business_license_no.max' => 'Business license no may not be greater than :max characters.',
            'bin_gst_sst_type.max' => 'BIN/GST/SST type may not be greater than :max characters.',
            'bin_gst_sst_no.max' => 'BIN/GST/SST No. may not be greater than :max characters.',
            'phone.required' => 'Contact phone is required',
            'phone.digits_between' => 'Phone number must be between :min and :max characters',
            'company_reg_no.max' => 'Company Reg. no may not be greater than :max characters.',
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
