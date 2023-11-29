<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsExpenseHeadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'billing_type'                  => ['string', 'max:255'],
            'head_id'                       => ['numeric', 'max:20'],
            'name'                          => ['required', 'string', 'max:255'],
            'is_visible_in_voyage_report'   => ['string', 'max:255'],
            'business_unit'                 => ['required', 'string', 'max:255'],
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
            'billing_type.max' => 'Port Name may not be greater than :max characters.',
            'head_id.numeric' => 'Port Name may not be greater than :max characters.',
            'head_id.max' => 'Port Name may not be greater than :max characters.',
            'code.required' => 'Port code is required',
            'code.unique' => 'Port code is already taken',
            'code.max' => 'Port code may not be greater than :max characters.',
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
