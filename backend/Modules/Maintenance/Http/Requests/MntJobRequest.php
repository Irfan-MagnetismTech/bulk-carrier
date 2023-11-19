<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntJobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id' => 'required',
            'mnt_item_id' => ['required', Rule::unique('mnt_jobs')->where('ops_vessel_id', $this->ops_vessel_id)->ignore($this->id)],
            'mntJobLines.*.job_description' => 'required|max:255',
            'mntJobLines.*.cycle' => 'required|integer|min:1',
            'mntJobLines.*.cycle_unit' => 'required|in:Hours,Days,Weeks,Months',
            'mntJobLines.*.min_limit' => 'required|integer|lt:mntJobLines.*.cycle',
            'mntJobLines.*.last_done' => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'mnt_item_id.unique' => 'Jobs for selected items on selected vessel already exists.',
            'mntJobLines.*.job_description.required' => 'Job description is a required field.',
            'mntJobLines.*.job_description.max' => 'Job description must not exceed 255 characters.',
            'mntJobLines.*.cycle.required' => 'Please enter Cycle number.',
            'mntJobLines.*.cycle.min' => 'Cycle number should be minimum 1.',
            'mntJobLines.*.cycle.integer' => 'Cycle should be a number.',
            'mntJobLines.*.cycle_unit.required' => 'Please select Cycle Unit.',
            'mntJobLines.*.cycle_unit.in' => 'Cycle unit value is invalid.',
            'mntJobLines.*.min_limit.lt' => 'Add to upcoming list value should be less than Cycle value.',
            'mntJobLines.*.min_limit.required' => 'Add to upcoming list is a required field.',
            'mntJobLines.*.min_limit.integer' => 'Add to upcoming list should be a number.',
            'mntJobLines.*.last_done.required' => 'Last done is a required field.',
            'mntJobLines.*.last_done.date' => 'Last done should be a valid date.',

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
