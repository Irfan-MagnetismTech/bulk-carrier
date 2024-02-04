<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwAttendanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'ops_vessel_id'                                  => ['required', 'integer', 'exists:ops_vessels,id'],
            'year_month'                                     => ['required', 'date', 'date_format:Y-m', Rule::unique('crw_attendances')->where('ops_vessel_id', $this->ops_vessel_id)->ignore($this->id)],

            'working_days'                                   => 'required|integer',
            'total_crews'                                    => 'required|integer',
            'remarks'                                        => 'nullable|string|max:500',
            'business_unit'                                  => 'required|in:PSML,TSLL',
            'crwAttendanceLines.*.crw_crew_id'               => ['required', 'integer', 'exists:crw_crew_profiles,id'],
            'crwAttendanceLines.*.crw_crew_assignment_id'    => ['required', 'integer', 'exists:crw_crew_assignments,id'],
            'crwAttendanceLines.*.attendance_line_composite' => 'required|string',
            'crwAttendanceLines.*.present_days'              => 'required|integer',
            'crwAttendanceLines.*.absent_days'               => 'required|integer',
            'crwAttendanceLines.*.payable_days'              => 'required|integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'ops_vessel_id.exists'                               => 'The Vessel Name does not exists.',
            'year_month.unique'                                  => 'A record with the combination of month-year and vessel name already exists.',
            'crwAttendanceLines.*.crw_crew_id.exists'            => 'The Crew Name does not exists[:position].',
            'crwAttendanceLines.*.crw_crew_assignment_id.exists' => 'The Assignment does not exists[:position].',
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
