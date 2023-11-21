<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'instrument_no'             => ['max:255'],
            'bill_no'                   => ['max:255'],
            'mr_no'                     => ['max:255'],
            'narration'                 => ['max:700'],
            'ledgerEntries.*.reff_bill' => ['max:255'],
            'ledgerEntries.*.dr_amount' => ['required', 'numeric', 'max:999999999999.99'],
            'ledgerEntries.*.cr_amount' => ['required', 'numeric', 'max:999999999999.99'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'instrument_no.max'             => 'The cheque number field must not exceed 255 characters.',
            'bill_no.max'                   => 'The bill no field must not exceed 255 characters.',
            'mr_no.max'                     => 'The mr no field must not exceed 255 characters.',
            'narration.max'                 => 'The narration field must not exceed 700 characters.',

            'ledgerEntries.*.reff_bill.max' => 'The reff bill[:index] field must not exceed 255 characters.',
            'ledgerEntries.*.dr_amount.max' => 'The dr amount[:index] field must not exceed 999999999999.99.',
            'ledgerEntries.*.cr_amount.max' => 'The cr amount[:index] field must not exceed 999999999999.99.',
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
