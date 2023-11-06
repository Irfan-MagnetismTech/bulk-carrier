<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsChartererContractRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
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
            'ops_vessel_id'                 => ['required', 'numeric', 'max:10'],
            'ops_charterer_profile_id'      => ['nullable', 'numeric', 'max:10'],
            'country'                       => ['required', 'string', 'max:255'],
            'address'                       => ['required', 'string', 'max:255'],
            'billing_address'               => ['required', 'string', 'max:255'],
            'email'                         => ['required', 'email', 'max:255'],
            'contact_no'                    => ['required', 'string', 'max:255'],
            'bank_branch_id'               => ['nullable', 'numeric', 'max:10'],
            'bank_branch_name'               => ['nullable', 'string'],
            // 'attachment'                    => ['nullable', 'mimes:png,jpeg,jpg,pdf,xlsx,docx,doc|max:2048'],
            'bank_id'                       => ['nullable', 'numeric', 'max:10'],
            'bank_name'                       => ['nullable', 'string'],
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
