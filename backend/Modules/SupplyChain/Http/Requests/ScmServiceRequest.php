<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ScmServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', Rule::unique('scm_services')->ignore($this->service, 'name')],
            'short_code' => ['required', 'max:255', Rule::unique('scm_services')->ignore($this->service, 'short_code')],
            'description' => 'max:255',
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

            'short_code.unique' => 'Short code is already taken',
            'short_code.required' => 'Short code is required',
            'short_code.max' => 'Short code is too long',

            'description.max' => 'Description is too long',
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
