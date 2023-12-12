<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccBalanceAndIncomeLineRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'line_text'     => ['required', 'string', 'max:255',
                                Rule::unique('acc_balance_and_income_lines')->ignore($this->id),
                            ],
            'business_unit' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'line_text.unique'   => 'The Balance Income Name field is exists.',
            'line_text.max'      => 'The Line Name field is required.',
            'line_text.max'      => 'The Line Name field must not exceed 255 characters.'
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
