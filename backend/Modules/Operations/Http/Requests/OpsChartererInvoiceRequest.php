<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsChartererInvoiceRequest extends FormRequest
{
    protected function prepareForValidation(){
        $data=  request('info');
        $this->replace(json_decode($data, true));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_charterer_profile_id'      => ['required', 'numeric', 'max:20'],
            'ops_charterer_contract_id'     => ['required', 'numeric'],
            'ops_voyage_id'                 => ['nullable', 'numeric', 'max:20'],
            'contract_type'                 => ['nullable', 'string', 'max:255'],
            'bill_from'                     => ['nullable'],
            'bill_till'                     => ['nullable'],
            'total_days'                    => ['nullable', 'numeric'],
            'total_amount'                  => ['required', 'numeric'],
            'per_day_charge'                => ['nullable', 'numeric'],
            'others_billable_amount'        => ['required', 'numeric'],
            'service_fee_deduction_amount'  => ['required', 'numeric'],
            // 'discount_unit'                 => ['required', 'string'],
            'discounted_amount'             => ['required', 'numeric'],
            'grand_total'                   => ['required', 'numeric'],
            // 'business_unit'                 => ['required', 'string', 'max:255'],
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
            'ops_charterer_profile_id.required' => 'Charterer name  is required',
            'ops_charterer_contract_id.required' => 'Charterer owner code is required',
            'ops_voyage_id.required' => 'Charterer owner code is required',
            'contract_type.required' => 'Contract type is required',
            // 'contract_type.max' => 'Contract type may not be greater than :max characters.',
            'bill_from.required' => 'Contract type is required',
            // 'contract_type.max' => 'Contract type may not be greater than :max characters.',
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
