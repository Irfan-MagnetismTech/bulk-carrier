<?php

namespace Modules\Operations\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OpsChartererProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_legal_name'    => ['required', 'string', 'max:255',Rule::unique('ops_charterer_profiles')->ignore($this->route('charterer_profile'), 'id')],
            'name'                  => ['required', 'string', 'max:255'],
            'owner_code'            => ['required', 'string', 'max:20', Rule::unique('ops_charterer_profiles')->ignore($this->route('charterer_profile'), 'id')],
            'country'               => ['required', 'string', 'max:255'],
            'contact_no'            => ['required', 'min:10', 'max:15', new PhoneNumber],
            'address'               => ['required', 'string', 'max:255'],
            'billing_address'       => ['required', 'string', 'max:255'],
            'billing_email'         => ['required', 'email', 'max:255'],
            'email'                 => ['required', 'email', 'max:255'],
            'website'               => ['nullable', 'string', 'max:255'],
            'business_unit'         => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.bank_id' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.bank_branch_id' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.bank_name' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.bank_branch_name' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.account_name' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.account_no' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.swift_code' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.routing_no' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.currency' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.country' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.state_division' => ['nullable', 'string', 'max:255'],
            'opsChartererBankAccounts.*.city' => ['nullable', 'string', 'max:255'],
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
            'company_legal_name.unique' => 'Company legal name is already taken.',
            'company_legal_name.required' => 'Company legal name is required.',
            'company_legal_name.max' => 'Company legal name may not be greater than :max characters.',
            'name.required' => 'Charterer Name is required.',
            'name.max' => 'Charterer Name may not be greater than :max characters.',
            'owner_code.required' => 'Charterer owner code is required.',
            'owner_code.unique' => 'Charterer owner code is already taken.',
            'owner_code.max' => 'Charterer owner code may not be greater than :max characters.',
            'country.required' => 'Country is required.',
            'country.max' => 'Country may not be greater than :max characters.',
            'contact_no.required' => 'Contact No. is required.',
            'contact_no.digits_between' => 'Contact No. must be between :min and :max characters',
            'address.required' => 'Address is required.',
            'address.max' => 'Address may not be greater than :max characters.',
            'billing_address.required' => 'Billing address is required.',
            'billing_address.max' => 'Billing address may not be greater than :max characters.',
            'billing_email.required' => 'Billing email is required',
            'billing_email.email' => 'Please enter a valid billing email',
            'billing_email.max' => 'Billing email may not be greater than :max characters.',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'email.max' => 'Email may not be greater than :max characters.',
            'website.max' => 'Website may not be greater than :max characters.',
            'opsChartererBankAccounts.*.bank_id.max' => 'Bank not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.bank_branch_id.max' => 'Bank branch not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.bank_name.max' => 'Bank Name may not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.bank_branch_name.max' => 'Bank Branch may not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.account_name.max' => 'Account Name may not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.account_no.max' => 'Account No may not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.swift_code.max' => 'Swift code may not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.routing_no.max' => 'Routing No. may not be greater than :max characters for row is :position.',
            'opsChartererBankAccounts.*.currency.max' => 'Currency may not be greater than :max characters for row is :position.',
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
