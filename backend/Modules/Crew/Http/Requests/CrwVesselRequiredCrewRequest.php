<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'effective_date'                                 => ['required', Rule::unique('crw_vessel_required_crews')->where('effective_date', $this->effective_date)->ignore($this->id)],
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
            'effective_date.unique'                                   => 'A record with the combination of effective date and vessel name already exists.',
            'effective_date.required'                                 => 'The effective date field is required.',
            'total_crew.required'                                     => 'The total crew field is required.',
            'total_crew.max'                                          => 'The total crew field must not exceed 2000.',
            'crwVesselRequiredCrewLines.*.required_manpower.required' => 'The required manpower[:position] field is required.',
            'crwVesselRequiredCrewLines.*.required_manpower.max'      => 'The required manpower[:position] field must not exceed 2000.',
            'crwVesselRequiredCrewLines.*.eligibility.max'            => 'The Eligibility[:position] field cannot exceed 700 characters.',
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
