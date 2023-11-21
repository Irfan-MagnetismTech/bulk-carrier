<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwAgencyBillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_agency_id'                    => 'required|integer',
            'crw_agency_contract_id'           => 'required|integer',
            'applied_date'                     => 'required|date',
            'invoice_date'                     => 'required|date',
            'invoice_no'                       => 'required|string|max:255',
            'invoice_type'                     => 'required|string|max:255',
            'invoice_currency'                 => 'required|string|max:255',
            'invoice_amount'                   => 'required|numeric',
            'grand_total'                      => 'required|numeric',
            'discount'                         => 'numeric',
            'net_amount'                       => 'required|numeric',
            'remarks'                          => 'nullable|string|max:700',
            'business_unit'                    => 'required|in:PSML,TSLL',
            'crwAgencyBillLines'               => 'required|array',
            'crwAgencyBillLines.*.particular'  => 'required|string|max:255',
            'crwAgencyBillLines.*.description' => 'nullable|string|max:700',
            'crwAgencyBillLines.*.per'         => 'nullable|string|max:255',
            'crwAgencyBillLines.*.quantity'    => 'required|integer',
            'crwAgencyBillLines.*.rate'        => 'required|numeric',
            'crwAgencyBillLines.*.amount'      => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crwAgencyBillLines.*.particular.max'  => 'Bill lines [line :position] particular field must not be greater than :max characters.',
            'crwAgencyBillLines.*.description.max' => 'Bill lines [line :position] description field must not be greater than :max characters.',
            'crwAgencyBillLines.*.per.max'         => 'Bill lines [line :position] per field must not be greater than :max characters.',
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
