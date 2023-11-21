<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwAgencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'name'                                 => 'required|string',
            'legal_name'                           => 'required|string',
            'tax_identification'                   => 'nullable|string',
            'business_license_no'                  => 'nullable|string',
            'company_reg_no'                       => 'nullable|string',
            'address'                              => 'required|string',
            'phone'                                => 'required|string',
            'email'                                => 'required|email',
            'website'                              => 'nullable|url',
            'logo'                                 => 'nullable',
            'country'                              => 'nullable|string',
            'business_unit'                        => 'required|in:PSML,TSLL',
            'crwAgencyContactPersons'              => 'required|array',
            'crwAgencyContactPersons.*.name'       => 'required|string',
            'crwAgencyContactPersons.*.contact_no' => 'required|string',
            'crwAgencyContactPersons.*.email'      => 'nullable|email',
            'crwAgencyContactPersons.*.position'   => 'nullable|string',
            'crwAgencyContactPersons.*.purpose'    => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
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
