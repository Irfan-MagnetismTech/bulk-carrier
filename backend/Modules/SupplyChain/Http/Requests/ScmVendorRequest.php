<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ScmVendorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'scmVendorContactPersons.*.email' => ['required', 'email', 'max:255'],
            'scmVendorContactPersons.*.phone' => [
                'required',
                'max:255',
                Rule::unique('scm_vendor_contact_persons', 'phone')
                    ->ignore($this?->vendor?->id, 'scm_vendor_id'),
            ],
            'scmVendorContactPersons.*.name' => ['required', 'string', 'max:255'],

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
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'scmVendorContactPersons.*.email.required' => 'Email is required',
            'scmVendorContactPersons.*.email.email' => 'Email is not valid',
            'scmVendorContactPersons.*.email.max' => 'Email is too long',
            'scmVendorContactPersons.*.phone.required' => 'Phone is required',
            'scmVendorContactPersons.*.phone.unique' => 'Phone is already taken',
            'scmVendorContactPersons.*.phone.max' => 'Phone is too long',
            'scmVendorContactPersons.*.name.required' => 'Name is required',
            'scmVendorContactPersons.*.name.max' => 'Name is too long',
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
