<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageBudgetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'         => ['required', 'numeric', 'max:20'],
            'ops_voyage_id'         => ['required', 'numeric', 'max:20'],
            'ops_expense_head_id'   => ['nullable', 'numeric', 'max:20'],
            'title'                 => ['required', 'string'],
            'total'                 => ['required', 'numeric'],
            'business_unit'         => ['required', 'string', 'max:255'],
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
