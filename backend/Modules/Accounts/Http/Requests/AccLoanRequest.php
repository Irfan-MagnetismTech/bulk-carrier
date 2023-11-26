<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccLoanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'loanable_type'     => 'required|string',
            'loanable_id'       => 'required|integer',
            'loan_type'         => 'required|string',
            'loan_number'       => 'required|string',
            'loan_name'         => 'required|string',
            'total_sanctioned'  => 'required|numeric|min:0',
            'sanctioned_limit'  => 'required|numeric|min:0',
            'total_installment' => 'required|integer|min:1',
            'interest_rate'     => 'required|numeric|min:0|max:100',
            'opening_date'      => 'required|date',
            'maturity_date'     => 'required|date|after_or_equal:opening_date',
            'emi_date'          => 'required|date',
            'emi_amount'        => 'required|numeric|min:0',
            'loan_purpose'      => 'required|string',
            'mortgage'          => 'nullable|string',
            'remarks'           => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
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
