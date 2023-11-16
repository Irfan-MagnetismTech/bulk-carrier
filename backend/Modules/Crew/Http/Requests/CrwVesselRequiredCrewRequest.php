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
            'crwVesselRequiredCrewLines.*.required_manpower' => ['required', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crwVesselRequiredCrewLines.*.required_manpower.max' => 'The Required Manpower[:index] field must not be greater than 2000.',
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
