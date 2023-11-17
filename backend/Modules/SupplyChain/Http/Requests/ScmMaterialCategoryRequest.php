<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ScmMaterialCategoryRequest extends FormRequest
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
                Rule::unique('scm_material_categories')->ignore($this->material_category, 'name'), 
                'max:255'
            ],

            'short_code' => [
                'required',
                Rule::unique('scm_material_categories')->ignore($this->material_category, 'short_code'), 
                'max:255'
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

            'short_code.required' => 'Short code is required',
            'short_code.unique' => 'Short code is already taken',
            'short_code.max' => 'Short code is too long',
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
