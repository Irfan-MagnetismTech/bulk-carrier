<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwCrewChecklistRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'effective_date'                    => ['required'],
            'business_unit'                     => ['required', 'string', 'max:255'],
            'crwCrewChecklistLines.*.item_name' => ['required', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'effective_date.required'               => 'The Effective Date field is required.',
            'crwCrewChecklistLines.*.item_name.max' => "The Item [:index] ". intval('[:index]')+1 ." field cannot exceed 255 characters.",
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
