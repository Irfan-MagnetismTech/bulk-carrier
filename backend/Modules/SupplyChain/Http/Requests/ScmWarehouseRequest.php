<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ScmWarehouseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('scm_warehouses')->ignore($this->warehouse, 'name'), 'max:255'],
            'cost_center_id' => 'required|exists:acc_cost_centers,id|integer',
            'address' => 'max:255',
            'short_code' => ['required', Rule::unique('scm_warehouses')->ignore($this->warehouse, 'short_code'), 'max:255'],
            'scmWarehouseContactPersons.*.name' => ['required', 'max:255'],
            'scmWarehouseContactPersons.*.designation' => ['required', 'max:255'],
            'scmWarehouseContactPersons.*.phone' => ['required', 'max:255'],
            'scmWarehouseContactPersons.*.email' => ['required', 'email', 'max:255'],
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
            'name.unique' => 'Name is already taken',
            'name.max' => 'Name is too long',
            'cost_center_id.required' => 'Cost center is required',
            'cost_center_id.exists' => 'Cost center is not found',
            'cost_center_id.integer' => 'Cost center must be an integer',
            'address.max' => 'Address is too long',
            'short_code.required' => 'Short code is required',
            'short_code.unique' => 'Short code is already taken',
            'short_code.max' => 'Short code is too long',
            'scmWarehouseContactPersons.*.name.required' => 'Name is required',
            'scmWarehouseContactPersons.*.name.max' => 'Name is too long',
            'scmWarehouseContactPersons.*.designation.required' => 'Designation is required',
            'scmWarehouseContactPersons.*.designation.max' => 'Designation is too long',
            'scmWarehouseContactPersons.*.phone.required' => 'Phone is required',
            'scmWarehouseContactPersons.*.phone.max' => 'Phone is too long',
            'scmWarehouseContactPersons.*.email.required' => 'Email is required',
            'scmWarehouseContactPersons.*.email.email' => 'Email is not valid',
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
