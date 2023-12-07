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
            'ops_charterer_profile_id'      => ['required', 'numeric', 'max:20'],
            'ops_charterer_contract_id'     => ['required', 'numeric'],
            // 'ops_voyage_id'                 => 'required_if:contract_type,==,Voyage Wise|nullable|max:20',
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
            'opsChartererInvoiceVoyages.*.ops_voyage_id' => 'required_if:contract_type,==,Voyage Wise|nullable|max:20',
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
            'contract_type.required' => 'Contract type is required',
            'bill_from.required' => 'Contract type is required',
            'opsChartererInvoiceVoyages.*.ops_voyage_id.required' => 'Voyage is required for row is :position.',
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
