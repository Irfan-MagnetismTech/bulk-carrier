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
            'mntJobLines.*.job_description' => 'required',
            'mntJobLines.*.cycle' => 'required|integer',
            'mntJobLines.*.cycle_unit' => 'required',
            'mntJobLines.*.min_limit' => 'required|integer',
            'mntJobLines.*.next_due' => 'required',
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
