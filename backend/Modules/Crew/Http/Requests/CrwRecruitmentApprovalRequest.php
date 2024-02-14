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

            'crwRecruitmentApprovalLines.*.candidate_name'    => ['required', 'string', 'max:255'],
            'crwRecruitmentApprovalLines.*.candidate_contact' => ['required', 'string', 'max:255'],
            'crwRecruitmentApprovalLines.*.candidate_email'   => ['max:255'],
            'crwRecruitmentApprovalLines.*.crw_rank_id'       => ['required', 'numeric', 'exists:crw_ranks,id'],            
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crew_selected.max'                                  => 'The total selected field must not exceed 2000.',
            'crew_panel.max'                                     => 'The total panel field must not exceed 2000.',
            'crew_rest.max'                                      => 'The total rest field must not exceed 2000.',
            'crwRecruitmentApprovalLines.*.candidate_name.max'    => 'The candidate name[:index] field must not exceed 255 characters.',
            'crwRecruitmentApprovalLines.*.candidate_contact.max' => 'The candidate contact[:index] field must not exceed 255 characters.',
            'crwRecruitmentApprovalLines.*.candidate_email.max'   => 'The candidate email[:index] field must not exceed 255 characters.',
            'crwRecruitmentApprovalLines.*.crw_rank_id.exists'    => 'The Rank does not exists[:position].',            
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
