<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccLoanReceivedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'acc_loan_id'             => 'required|numeric',
            'received_date'           => 'required|date',
            'payment_method'          => 'required',
            'received_acc_account_id' => 'required|numeric',
            'instrument_no'           => 'required',
            'instrument_date'         => 'required|date',
            'received_amount'         => 'required|numeric',
            'interest_rate'           => 'required|numeric|min:0|max:100',
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
