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
            'code'                  => ['string', 'max:20', Rule::unique('ops_customers')->ignore($this->route('customer'), 'id')],
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
            'phone' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if (strlen((string) $value) < 10) {
                    $fail('The ' . $attribute . ' must be at least 10 characters.');
                }
            }],
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
