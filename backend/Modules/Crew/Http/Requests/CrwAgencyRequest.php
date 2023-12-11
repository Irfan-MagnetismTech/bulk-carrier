<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwAgencyRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data      = request('data');
        $dataArray = json_decode($data, true);
        $logo      = is_object(request('logo')) ? request('logo') : null;
        $mergeData = array_merge($dataArray, ['logo' => $logo]);

        $this->replace($mergeData);
    }

    public function rules(): array {
        return [
            'agency_name'                          => 'required|string|max:255',
            'legal_name'                           => 'required|string|max:255',
            'tax_identification'                   => 'nullable|string|max:255',
            'business_license_no'                  => 'nullable|string|max:255',
            'company_reg_no'                       => 'nullable|string|max:255',
            'address'                              => 'required|string|max:255',
            'phone'                                => ['required', 'string', 'max:255', Rule::unique('crw_agencies')->where('business_unit', $this->business_unit)->ignore($this->id)],
            'email'                                => 'required|email|max:255',
            'website'                              => 'nullable|url|max:255',
            'logo'                                 => 'nullable|image|mimes:jpeg,png,gif,svg|max:2048',
            'country'                              => 'nullable|string|max:255',
            'business_unit'                        => 'required|in:PSML,TSLL',
            'crwAgencyContactPersons'              => 'required|array',
            'crwAgencyContactPersons.*.name'       => 'required|string|max:255',
            'crwAgencyContactPersons.*.contact_no' => 'required|string|max:255',
            'crwAgencyContactPersons.*.email'      => 'nullable|email|max:255',
            'crwAgencyContactPersons.*.position'   => 'nullable|string|max:255',
            'crwAgencyContactPersons.*.purpose'    => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crwAgencyContactPersons.*.name.max'       => 'Contact person [line :position] name field must not be greater than :max characters.',
            'crwAgencyContactPersons.*.contact_no.max' => 'Contact person [line :position] contact_no field must not be greater than :max characters.',
            'crwAgencyContactPersons.*.email.max'      => 'Contact person [line :position] email field must not be greater than :max characters.',
            'crwAgencyContactPersons.*.position.max'   => 'Contact person [line :position] position field must not be greater than :max characters.',
            'crwAgencyContactPersons.*.purpose.max'    => 'Contact person [line :position] purpose field must not be greater than :max characters.',
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
