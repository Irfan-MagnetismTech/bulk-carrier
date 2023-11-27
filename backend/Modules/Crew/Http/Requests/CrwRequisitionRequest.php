<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwRequisitionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'applied_date'                                => ['required'],
            'total_required_manpower'                     => ['required', 'numeric', 'max:2000'],
            'business_unit'                               => ['required', 'string', 'max:255'],
            'crwCrewRequisitionLines.*.required_manpower' => ['required', 'numeric', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'applied_date.required'                           => 'The applied date Required.',
            'total_required_manpower.max'                     => 'The total crew field must not exceed 2000.',
            'crwCrewRequisitionLines.*.required_manpower.max' => 'The required manpower[:index] field must not exceed 2000.',
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
