<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MntCriticalSpListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'reference_no' => 'required',
            'record_date' => 'required',
            'mntCriticalSpListLines.*.*.rob' => 'integer|min:1',
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
            'mntCriticalSpListLines.*.rob.required' => 'ROB is required for row no. :position',
            'mntCriticalSpListLines.*.rob.integer' => 'ROB should be a number for row no. :position',
            'mntCriticalSpListLines.*.rob.min' => 'ROB should have minimum 1 for row no. :position',
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
