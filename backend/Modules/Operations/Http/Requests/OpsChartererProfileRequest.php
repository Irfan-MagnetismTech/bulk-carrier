<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsChartererProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_legal_name'    => ['required', 'string', 'max:255'],
            'name'                  => ['required', 'string', 'max:255'],
            'owner_code'            => ['string', 'max:20', Rule::unique('ops_charterer_profiles')->ignore($this->ops_charterer_profile)],
            'country'               => ['required', 'string', 'max:255'],
            'contact_no'            => ['required', 'string', 'max:255'],
            'address'               => ['required', 'string', 'max:255'],
            'billing_address'       => ['required', 'string', 'max:255'],
            'billing_email'         => ['required', 'email', 'max:255'],
            'email'                 => ['required', 'email', 'max:255'],
            'website'               => ['nullable', 'string', 'max:255'],
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
