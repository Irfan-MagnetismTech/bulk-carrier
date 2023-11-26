<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntWorkRequisitionWipRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
        'status' => 'required',
        'act_start_date' => 'required_if:status,1|date|after_or_equal:requisition_date',
        'act_completion_date' => 'nullable|date|after_or_equal:act_start_date',
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
            'ops_vessel_id.required' => 'Please select vessel.',
            'reference_no.required' => 'Reference no. is required',
            'mnt_item_id.required' => 'Item is required',
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
