<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwRankRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'short_name'    => ['required', 'string', 'max:6'],
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
            'name.max'      => 'The Rank Name field cannot exceed 255 characters.',
            'name.required' => 'The Rank Name field is required.',

            'short_name.max'      => 'The Short Name field cannot exceed 6 characters.',
            'short_name.required' => 'The Short Name field is required.',
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
