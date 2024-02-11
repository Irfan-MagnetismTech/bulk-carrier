<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpsVesselExpenseHeadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 'ops_vessel_id' => [
            //     'required',
            //     'integer',
            //     'max:255',
            //     // Rule::unique('ops_vessel_expense_heads', 'ops_vessel_id')->ignore($this->route('vessel_expense_head'), 'id'),
            // ],
            'ops_vessel_id' => 'required|integer|max:255|exists:ops_vessels,id',

            // 'ops_expense_head_id'   => ['required', 'numeric', 'max:50'],
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
            'ops_vessel_id.required' => 'Vessel is required.',
            'ops_vessel_id.exists' => 'Vessel is not valid',
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
