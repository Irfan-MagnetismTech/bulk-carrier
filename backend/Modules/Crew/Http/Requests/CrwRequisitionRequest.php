<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwRequisitionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        $id = $this->route('crw_requisition');
        // dd($id);

        return [
            'applied_date'                                => ['required'],
            'total_required_manpower'                     => ['required', 'numeric', 'max:2000',
                                                                Rule::unique('crw_crew_requisitions')->where('ops_vessel_id', $this->ops_vessel_id)->where('applied_date', $this->applied_date)->ignore($id)],
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
            'total_required_manpower.unique'                  => 'A record with the combination of vessel name, applied date and total crew already exists.',
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
