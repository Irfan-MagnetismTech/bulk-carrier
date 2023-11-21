<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ScmMaterialRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('scm_materials')->ignore($this->material, 'name'),
                'max:255',
                'string'
            ],

            'material_code' => [
                'required',
                Rule::unique('scm_materials')->ignore($this->material, 'material_code'),
                'max:255',
                'string'
            ],

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
            'name.string' => 'Name must be a string',

            'material_code.required' => 'Material code is required',
            'material_code.unique' => 'Material code is already taken',
            'material_code.max' => 'Material code is too long',
            'material_code.string' => 'Material code must be a string',
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
