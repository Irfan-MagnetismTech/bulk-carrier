<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpsContractAssignRequest extends FormRequest
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
            'ops_vessel_id'             => ['required', 'exists:ops_vessels,id'],
            'ops_voyage_id'             => ['required', 'exists:ops_voyages,id',Rule::unique('ops_contract_assigns')->ignore($this->route('contract_assign'), 'id')],
            'ops_charterer_contract_id' => 'required_if:contract_assign_type,==,Charterer|nullable|max:20|exists:ops_charterer_contracts,id',
            'ops_charterer_profile_id'  => 'required_if:contract_assign_type,==,Charterer|nullable|max:20|exists:ops_charterer_profiles,id',
            'ops_customer_id'           => 'required_if:contract_assign_type,==,Customer|nullable|max:20|exists:ops_customers,id',
            'assign_date'               => ['required', 'date'],
            'opsVoyage.opsContractTariffs.*.ops_cargo_tariff_id'    =>  'required_if:contract_assign_type,==,Customer|nullable|max:20|exists:ops_cargo_tariffs,id',
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
            'ops_vessel_id.required' => 'Vessel is required.',
            'ops_vessel_id.exists' => 'Vessel is not valid.',
            'ops_voyage_id.unique' => 'Voyage is already taken.',
            'ops_voyage_id.required' => 'Voyage is required.',
            'ops_voyage_id.exists' => 'Voyage is not valid.',
            'ops_charterer_profile_id.required' => 'Charterer is required.',
            'ops_charterer_profile_id.exists' => 'Charterer is not valid.',
            'ops_charterer_contract_id.required' => 'Charterer Contract is required.',
            'ops_charterer_contract_id.exists' => 'Charterer Contract is not valid.',
            'ops_customer_id.required' => 'Customer is required.',
            'ops_customer_id.exists' => 'Customer is not valid.',
            'assign_date.required' => 'Assign Date is required.',
            'assign_date.date' => 'Assign Date must be a date.',
            'opsVoyage.opsContractTariffs.*.ops_cargo_tariff_id.required_if' => 'Cargo Tarrif can not be null.',
            'opsVoyage.opsContractTariffs.*.ops_cargo_tariff_id.exists' => 'Cargo Tarrif is not valid.',
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
