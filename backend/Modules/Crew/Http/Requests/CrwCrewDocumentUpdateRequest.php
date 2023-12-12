<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwCrewDocumentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'document_name'            => ['required', 'string', 'max:255', 
                                            Rule::unique('crw_crew_documents')->where('business_unit', $this->business_unit)
                                            ->where('crw_crew_profile_id', $this->crw_crew_profile_id)->ignore($this->id)
                                        ],
            'issuing_authority'        => 'required|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            //
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
