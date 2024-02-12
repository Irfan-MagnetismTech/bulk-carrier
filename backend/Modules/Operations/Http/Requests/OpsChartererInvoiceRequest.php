<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsChartererInvoiceRequest extends FormRequest
{
    protected function prepareForValidation(){
        $data=  request('info');
        // dd(json_decode($data, true));
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
            'ops_charterer_profile_id'      => ['required','exists:ops_charterer_profiles,id'],
            'ops_charterer_contract_id'     => ['required','exists:ops_charterer_contracts,id'],
            // 'ops_voyage_id'                 => 'required_if:contract_type,==,Voyage Wise|nullable|exists:ops_voyages,id',
            'contract_type'                 => ['nullable', 'string', 'max:255'],
            'bill_from'                     => 'required_if:contract_type,==,Day Wise|nullable',
            'bill_till'                     => 'required_if:contract_type,==,Day Wise|nullable',
            'total_days'                    => ['nullable', 'numeric'],
            'total_amount'                  => ['required', 'numeric'],
            'per_day_charge'                => ['nullable', 'numeric'],
            'others_billable_amount'        => ['nullable', 'numeric'],
            'service_fee_deduction_amount'  => ['nullable', 'numeric'],
            // 'discount_unit'                 => ['required', 'string'],
            'discounted_amount'             => ['nullable', 'numeric'],
            'grand_total'                   => ['required', 'numeric'],
            'opsChartererInvoiceVoyages.*.ops_voyage_id' => 'required_if:contract_type,==,Voyage Wise|nullable|max:20|exists:ops_voyages,id',
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
            'ops_charterer_profile_id.exists' => 'Charterer is not valid',
            'ops_charterer_contract_id.required' => 'Charterer owner code is required',
            'ops_charterer_contract_id.exists' => 'Charterer owner code is not valid',
            'contract_type.required' => 'Contract type is required',
            'bill_from.required' => 'Contract type is required',
            'opsChartererInvoiceVoyages.*.ops_voyage_id.required_if' => 'Voyage is required for row :position.',
            'opsChartererInvoiceVoyages.*.ops_voyage_id.exists' => 'Voyage is not valid for row :position.',
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
