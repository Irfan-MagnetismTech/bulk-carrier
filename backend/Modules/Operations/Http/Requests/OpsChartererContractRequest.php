<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsChartererContractRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
        // dd($mergeData);
        $this->replace($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'contract_type'                 => ['required', 'string', 'max:255'],
            'contract_name'                 => ['required', 'string', 'max:255'],
            'ops_vessel_id'                 => ['required', 'numeric'],
            'ops_charterer_profile_id'      => ['nullable', 'numeric'],
            'country'                       => ['required', 'string', 'max:255'],
            'address'                       => ['required', 'string', 'max:255'],
            'billing_address'               => ['required', 'string', 'max:255'],
            'email'                         => ['required', 'email', 'max:255'],
            'contact_no'                    =>  ['required', 'numeric', 'digits_between:10,15'],
            'bank_branch_id'               => ['nullable', 'numeric'],
            'bank_branch_name'               => ['nullable', 'string'],
            // 'attachment'                    => ['nullable', 'mimes:png,jpeg,jpg,pdf,xlsx,docx,doc|max:2048'],
            'bank_id'                       => ['nullable', 'numeric'],
            'bank_name'                       => ['nullable', 'string','max:255'],
            'bank_account_name'             => ['nullable', 'string', 'max:255'],
            'bank_account_no'               => ['nullable', 'string', 'max:255'],
            'swift_code'                    => ['nullable', 'string', 'max:255'],
            'routing_no'                    => ['nullable', 'string', 'max:255'],
            'currency'                      => ['nullable', 'string', 'max:255'],
            'status'                        => ['nullable', 'string', 'max:255'],
            'business_unit'                 => ['nullable', 'string', 'max:255'],


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
            'contract_type.required' => 'Contract type is required',
            'contract_type.max' => 'Contract type may not be greater than :max characters.',
            'contract_name.required' => 'Contract name is required',
            'contract_name.max' => 'Contract name may not be greater than :max characters.',
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_charterer_profile_id.required' => 'Charterer is required',
            'country.required' => 'Country is required',
            'country.max' => 'Country may not be greater than :max characters.',
            'address.required' => 'Address is required',
            'address.max' => 'Address may not be greater than :max characters.',
            'billing_address.required' => 'Billing address is required',
            'billing_address.max' => 'Billing address may not be greater than :max characters.',
            'contact_no.required' => 'Contact No. is required',
            'contact_no.digits_between' => 'Contact No. must be between :min and :max characters',
            'bank_name.max' => 'Bank Name may not be greater than :max characters.',
            'bank_branch_name.max' => 'Billing address may not be greater than :max characters.',
            'bank_account_name.max' => 'Bank account name may not be greater than :max characters.',
            'bank_account_no.max' => 'Bank account No. may not be greater than :max characters.',
            'swift_code.max' => 'Swift code may not be greater than :max characters.',
            'routing_no.max' => 'Routing No may not be greater than :max characters.',
            'currency.max' => 'Currency may not be greater than :max characters.',
            'status.max' => 'Status not be greater than :max characters.',
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
