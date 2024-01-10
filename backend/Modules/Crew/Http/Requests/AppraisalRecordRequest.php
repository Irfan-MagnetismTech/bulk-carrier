<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppraisalRecordRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_crew_id'                                => 'required|integer|exists:crw_crew_profiles,id',
            'appraisal_form_id'                          => 'required|integer|exists:appraisal_forms,id',
            'crw_crew_assignment_id'                     => [Rule::unique('appraisal_records')->ignore($this->id)], 
            // ['required', 'integer', 'exists:crw_crew_assignments,id', Rule::unique('appraisal_records')->ignore($this->id)],
            'appraisal_date'                             => 'required|date',
            'age'                                        => 'required|integer',
            'business_unit'                              => 'required|in:PSML,TSLL',
            'appraisalRecordLines.*.line_item_composite' => 'required',
            'appraisalRecordLines.*.comment'             => 'required|string',
            'appraisalRecordLines.*.answer'              => 'required|string',
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
