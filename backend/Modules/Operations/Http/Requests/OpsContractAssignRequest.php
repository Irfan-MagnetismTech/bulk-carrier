<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpsContractAssignRequest extends FormRequest
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
            'ops_vessel_id'             => ['required', 'max:20'],
            'ops_voyage_id'             => ['required', 'max:20',Rule::unique('ops_contract_assigns')->ignore($this->route('contract_assign'), 'id')],
            'ops_charterer_contract_id' => 'required_if:business_unit,!=,PSML|nullable|max:20',
            'ops_charterer_profile_id'  => 'required_if:business_unit,!=,PSML|nullable|max:20',
            'ops_cargo_tariff_id'       => 'required_if:business_unit,!=,TSLL|nullable|max:20',
            'ops_customer_id'           => 'required_if:business_unit,!=,TSLL|nullable|max:20',
            'assign_date'               => ['required'],            
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
            'ops_voyage_id.unique' => 'Voyage is already taken.',
            'ops_voyage_id.required' => 'Voyage is required.',
            'ops_charterer_profile_id.required' => 'Charterer is required.',
            'ops_charterer_contract_id.required' => 'Charterer Contract is required.',
            'ops_cargo_tariff_id.required' => 'Tarrif is required.',
            'ops_customer_id.required' => 'Customer is required.',
            'assign_date.required' => 'Assign Date is required.',
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
