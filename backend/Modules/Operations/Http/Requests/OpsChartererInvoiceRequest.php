<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsChartererInvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_charterer_profile_id'      => ['required', 'string', 'max:10'],
            'ops_charterer_contract_id'     => ['required', 'string', 'max:10'],
            'ops_voyage_id'                 => ['required', 'string', 'max:10'],
            'contract_type'                 => ['required', 'string', 'max:255'],
            'bill_from'                     => ['required', 'datetime', 'max:50'],
            'bill_till'                     => ['required', 'datetime', 'max:50'],
            'total_days'                    => ['required', 'string', 'max:50'],
            'total_amount'                  => ['required', 'string', 'max:50'],
            'others_billable_amount'        => ['required', 'string', 'max:50'],
            'service_fee_deduction_amount'  => ['required', 'string', 'max:50'],
            'discount_unit'                 => ['required', 'string', 'max:50'],
            'discounted_amount'             => ['required', 'string', 'max:50'],
            'grand_total'                   => ['required', 'string', 'max:50'],
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
