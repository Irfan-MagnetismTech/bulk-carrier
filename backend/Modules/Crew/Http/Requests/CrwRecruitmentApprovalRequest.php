<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwRecruitmentApprovalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'applied_date'                                   => ['required'],
            'page_title'                                     => ['required', 'string', 'max:255'],
            'subject'                                        => ['required', 'string', 'max:255'],
            'total_approved'                                 => ['required', 'numeric', 'max:2000'],
            'crew_agreed_to_join'                            => ['required', 'numeric', 'max:2000'],
            'crew_selected'                                  => ['required', 'numeric', 'max:2000'],
            'crew_panel'                                     => ['required', 'numeric', 'max:2000'],
            'crew_rest'                                      => ['required', 'numeric', 'max:2000'],
            'body'                                           => ['required', 'string', 'max:2000'],
            'business_unit'                                  => ['required', 'string', 'max:255'],

            'crwVesselRequiredCrewLines.*.candidate_name'    => ['required', 'string', 'max:255'],
            'crwVesselRequiredCrewLines.*.candidate_contact' => ['required', 'string', 'max:255'],
            'crwVesselRequiredCrewLines.*.candidate_email'   => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crwVesselRequiredCrewLines.*.candidate_name.max'    => 'The Candidate Name field must not exceed 255 characters.',
            'crwVesselRequiredCrewLines.*.candidate_contact.max' => 'The Candidate Contact field must not exceed 255 characters.',
            'crwVesselRequiredCrewLines.*.candidate_email.max'   => 'The Candidate Email field must not exceed 255 characters.',
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
