<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwCrewAssignmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'ops_vessel_id'      => 'required|integer',
            'crw_crew_id'        => ['required', 'integer', Rule::unique('crw_crew_assignments')->where('status', "Onboard")->ignore($this->id)],
            'position_onboard'   => 'required|string|max:255',
            'joining_date'       => 'required|date',
            'joining_port_code'  => 'required|string|max:10',
            'duration'           => 'required|numeric|max:120',
            // 'status'             => 'required|in:Onboard,Complete',
            // 'completion_date'    => 'nullable|date',
            // 'completion_remarks' => 'nullable|string|max:255',
            // 'remarks'            => 'nullable|string|max:255',
            'business_unit'      => 'required|in:PSML,TSLL',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crw_crew_id.unique'        => 'Complete the current onboard job before assigning a new job to this crew member.',
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
