<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
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
        // dd($this);
        return [
            'billing_type'  => ['nullable', 'string', 'max:255'],
            'head_id'       => 'nullable|numeric|max:50|exists:ops_expense_heads,id',
            // 'head_id'                       => ['nullable', 'numeric', 'max:50','exists:ops_expense_heads,id'],
            'name'          => [
                'required',
                'string',
                'max:255',
                // Rule::unique('ops_expense_heads', 'name')->where(function ($query) {
                //     return $this->id !== null;
                // }),
            ],
            // 'is_visible_in_voyage_report'   => ['boolean'],
            'business_unit' => ['required', 'string', 'max:255'],
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
            'billing_type.max' => 'Billing type may not be greater than :max characters.',
            'head_id.numeric' => 'Head must be numeric.',
            'head_id.exists' => 'Head is not valid',
            'name.required' => 'Name is required.',
            'name.unique' => 'This expense group name is already used.',
            'name.max' => 'Name may not be greater than :max characters.',
            // 'head_id.max' => 'Head may not be greater than :max characters.',
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
