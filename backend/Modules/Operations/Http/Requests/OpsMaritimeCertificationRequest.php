<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsMaritimeCertificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this);
        return [
            'name'  => ['required', 'string', 'max:255',Rule::unique('ops_maritime_certifications')->ignore($this->route('maritime_certification'), 'id')],
            'type'  => ['required', 'string', 'max:255'],
            'validity'  => ['required', 'string', 'max:50'],
            'authority'  => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

        /**
     * Get the error messages for the defined validation rules.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Certificate name is required',
            'name.unique' => 'Certificate name is already taken',
            'name.max' => 'Certificate name may not be greater than :max characters.',
            'type.required' => 'Certificate type is required',
            'type.max' => 'Certificate type may not be greater than :max characters.',
            'validity.required' => 'Validity period is required',
            'validity.max' => 'Validity period may not be greater than :max characters.',
            'authority.required' => 'Certificate authority is required',
            'authority.max' => 'Certificate authority may not be greater than :max characters.',
        ];
    }
}
