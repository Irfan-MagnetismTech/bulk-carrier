<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'account_name' => ['required', 'string', 'max:255',
                                Rule::unique('acc_accounts')->where('business_unit', $this->business_unit)->ignore($this->id),
                            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'account_name.unique'   => 'The Account name field is exists within this business unit.',
            'account_name.required' => 'The Account Name field is required.',
            'account_name.max'      => 'The Account Name field must not exceed 2000.',
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
