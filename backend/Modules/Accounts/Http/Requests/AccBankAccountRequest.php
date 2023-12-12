<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccBankAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'bank_name'       => ['required', 'string', 'max:255'],
            'branch_name'     => ['required', 'string', 'max:255'],
            'account_type'    => ['required', 'string', 'max:255'],
            'account_name'    => ['required', 'string', 'max:255'],
            'account_number'  => ['required', 'string', 'max:255',
                                Rule::unique('acc_bank_accounts')
                                ->where('business_unit', $this->business_unit) 
                                ->ignore($this->id),
                             ],
            'routing_number'  => ['required', 'string', 'max:255'],
            'contact_number'  => ['required', 'string', 'max:255'],
            'opening_date'    => ['required'],
            'opening_balance' => ['required', 'numeric', 'max:9999999999.99'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'account_number.unique'         => 'The Account Number field is exists within this business unit .',
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
