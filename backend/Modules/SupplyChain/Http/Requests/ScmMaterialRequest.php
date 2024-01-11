<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ScmMaterialRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $imgData = is_object(request('sample_photo')) ? request('sample_photo') : null;
        $mergeData = array_merge($this->all, ['sample_photo' => $imgData]);
    }

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
            'hs_code' => 'required|max:255|string',
            'sample_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:11048',
            'type' => 'required|string',
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
            'name.required' => ' Name is required',
            'name.unique' => ' Name is already taken',
            'name.max' => ' Name is too long',
            'name.string' => ' Name must be a string',

            'material_code.required' => ' Material code is required',
            'material_code.unique' => ' Material code is already taken',
            'material_code.max' => ' Material code is too long',
            'material_code.string' => ' Material code must be a string',

            'hs_code.required' => ' HS code is required',
            'hs_code.max' => ' HS code is too long',
            'hs_code.string' => ' HS code must be a string',

            'sample_photo.image' => ' Sample photo must be an image',
            'sample_photo.mimes' => ' Sample photo must be an image type (jpeg, png or jpg)',
            'sample_photo.max' => ' Sample photo must be less than 10 MB',

            'type.required' => ' Type is required',
            'type.string' => ' Type must be a string',
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
