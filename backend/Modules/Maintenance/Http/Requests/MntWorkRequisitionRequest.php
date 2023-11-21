<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntWorkRequisitionRequest extends FormRequest
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
        'reference_no' => ['required', Rule::unique('mnt_work_requisitions')->ignore($this->id)],
        'requisition_date' => 'required|date',
        'est_start_date' => 'required|date|after_or_equal:requisition_date',
        'est_completion_date' => 'required|date|after_or_equal:est_start_date',
        'responsible_person' => 'required',
        'assigned_to' => 'required',
        'mnt_item_id' => 'required',
        "added_job_lines"    => "required|array|min:1",
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
            "added_job_lines.required" => "Please add at least one job."
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
