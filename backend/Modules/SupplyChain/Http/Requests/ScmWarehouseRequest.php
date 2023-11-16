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
            'name' => ['required', Rule::unique('scm_warehouses')->ignore($this->warehouse, 'name')],
            'short_code' => ['required', Rule::unique('scm_warehouses')->ignore($this->warehouse, 'short_code')],
            'scmWarehouseContactPersons.*.email' => 'required|email',
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
            'short_code.required' => 'Short code is required',
            'short_code.unique' => 'Short code is already taken',
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
