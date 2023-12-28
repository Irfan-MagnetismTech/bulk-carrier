<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwPolicyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        $id = $this->route('crw_policy');

        return [
            'name'          => ['required', 'string', 'max:50', Rule::unique('crw_policies')->where('business_unit', $this->business_unit)->ignore($id)],
            'type'          => ['required', 'string'],
            'business_unit' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'name.max'      => 'The policy name field cannot exceed 50 characters.',
            'name.unique'   => 'The policy name field is exists within this business unit.',
            'name.required' => 'The policy name field is required.',
            'type.required' => 'The policy type field is required.',
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
