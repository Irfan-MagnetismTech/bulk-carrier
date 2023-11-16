<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwVesselRequiredCrewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'total_crew'                                     => ['required', 'numeric', 'max:2000'],
            'effective_date'                                 => ['required'],
            'business_unit'                                  => ['required', 'string', 'max:255'],
            'crwVesselRequiredCrewLines.*.required_manpower' => ['required', 'numeric', 'max:255'],
            'crwVesselRequiredCrewLines.*.eligibility'       => ['required', 'string', 'max:700'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'total_crew.required'                                => 'The Total Crew field is required.',
            'total_crew.max'                                     => 'The Total Crew field must not exceed 2000.',
            'crwVesselRequiredCrewLines.*.required_manpower.max' => 'The Required Manpower field must not exceed 2000.',
            'crwVesselRequiredCrewLines.*.eligibility.required'  => 'The Eligibility field is required.',
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
