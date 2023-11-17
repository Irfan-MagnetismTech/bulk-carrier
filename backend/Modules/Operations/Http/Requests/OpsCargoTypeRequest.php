<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpsCargoTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cargo_type'    => ['required', 'string', 'max:255', Rule::unique('ops_cargo_types')->ignore($this->route('cargo_type'), 'id')],
            'description'   => ['nullable', 'string', 'max:5000'],
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
            'cargo_type.required' => 'Cargo type is required',
            'cargo_type.unique' => 'Cargo type is already taken',
            'cargo_type.max' => 'Cargo type may not be greater than :max characters.',
            'description.max' => 'Cargo description may not be greater than :max characters.',
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
