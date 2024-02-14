<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwCrewProfileRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data      = request('data');
        $dataArray = json_decode($data, true);

        $picture    = is_object(request('picture')) ? request('picture') : null;
        $attachment = is_object(request('attachment')) ? request('attachment') : null;

        $mergeData = array_merge($dataArray, [
            'picture'    => $picture,
            'attachment' => $attachment,
        ]);

        $this->replace($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_recruitment_approval_id'    => 'integer|exists:crw_recruitment_approvals,id',
            'agency_id'                      => 'nullable|integer|exists:crw_agencies,id',
            'department_name'                => 'required|string|max:50',
            'crw_rank_id'                    => ['required', 'numeric', 'exists:crw_ranks,id'],
            'employee_type'                  => 'required|string|max:255',
            'is_officer'                     => 'required|boolean',
            'first_name'                     => 'required|string|max:100',
            'last_name'                      => 'required|string|max:100',
            'father_name'                    => 'required|string|max:255',
            'mother_name'                    => 'required|string|max:255',
            'date_of_birth'                  => 'required|date',
            'gender'                         => 'required',
            'religion'                       => 'required|string|max:255',
            'marital_status'                 => 'required|string|max:255',
            'nationality'                    => 'required|string|max:255',
            'nid_no'                         => 'string|max:255',
            'passport_no'                    => 'string|max:255',
            'passport_issue_date'            => 'nullable|date',
            'blood_group'                    => 'string|max:255',
            'height'                         => 'string|max:255',
            'weight'                         => 'string|max:255',
            'pre_address'                    => 'required|string|max:255',
            'pre_city'                       => 'required|string|max:255',
            'pre_mobile_no'                  => ['required', 'string', 'max:255', Rule::unique('crw_crew_profiles')->where('business_unit', $this->business_unit)->ignore($this->id)],
            'pre_email'                      => 'email|max:255',
            'per_address'                    => 'required|string|max:255',
            'per_city'                       => 'required|string|max:255',
            'per_mobile_no'                  => ['required', 'string', 'max:255', Rule::unique('crw_crew_profiles')->where('business_unit', $this->business_unit)->ignore($this->id)],
            'per_email'                      => 'email|max:255',
            'picture'                        => 'nullable|image|mimes:jpeg,png,gif,svg|max:2048',
            'attachment'                     => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif|max:2048',
            'business_unit'                  => 'required|in:PSML,TSLL',

            // Validation rules for educations_array
            'educations.*.exam_title'        => 'required|string|max:255|distinct',
            'educations.*.major'             => 'required|string|max:255',
            'educations.*.institute'         => 'required|string|max:255',
            'educations.*.result'            => 'required|string|max:255',
//            'educations.*.passing_year'      => 'required|date_format:Y',
            'educations.*.passing_year'      => 'required|integer|min:1000|max:9999',
            'educations.*.duration'          => 'string|max:255',
            'educations.*.achievement'       => 'string|max:255',

            // Validation rules for trainings_array
            'trainings'                      => 'nullable|array',
            'trainings.*.training_title'     => 'required|string|max:255|distinct',
            'trainings.*.covered_topic'      => 'required|string|max:255',
            'trainings.*.year'               => 'required|integer|min:1000|max:9999',
            'trainings.*.institute'          => 'required|string|max:255',
            'trainings.*.duration'           => 'string|max:255',
            'trainings.*.location'           => 'string|max:255',

            // Validation rules for experiences_array
            'experiences'                    => 'nullable|array',
            'experiences.*.employer_name'    => 'required|string|max:255',
            'experiences.*.from_date'        => 'required|date',
            'experiences.*.till_date'        => 'required|date',
            'experiences.*.last_designation' => 'required|string|max:255',
            'experiences.*.reason_for_leave' => 'string|max:255',

            // Validation rules for languages_array
            'languages'                      => 'nullable|array',
            'languages.*.language_name'      => 'required|string|max:255|distinct',
            'languages.*.writing'            => 'required|string|max:255',
            'languages.*.reading'            => 'required|string|max:255',
            'languages.*.speaking'           => 'required|string|max:255',
            'languages.*.listening'          => 'required|string|max:255',

            // Validation rules for references_array
            'references'                     => 'nullable|array',
            'references.*.name'              => 'required|string|max:255|distinct',
            'references.*.organization'      => 'required|string|max:255',
            'references.*.designation'       => 'required|string|max:255',
            'references.*.address'           => 'required|string|max:255',
            'references.*.contact_personal'  => 'required|string|max:255',
            'references.*.contact_office'    => 'string|max:255',
            'references.*.email'             => 'email|max:255',
            'references.*.relation'          => 'required|string|max:255',

            // Validation rules for nominees_array
            'nominees'                       => 'nullable|array',
            'nominees.*.name'                => 'required|string|max:255|distinct',
            'nominees.*.profession'          => 'required|string|max:255',
            'nominees.*.address'             => 'required|string|max:255',
            'nominees.*.relationship'        => 'required|string|max:255',
            'nominees.*.contact_no'          => 'string|max:255',
            'nominees.*.email'               => 'email|max:255',
            'nominees.*.is_relative'         => 'required|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crw_agency_id'               => 'The Agency Name does not exists.',
            'crw_recruitment_approval_id' => 'The Recruitment Approval does not exists.',
            'pre_mobile_no.unique'        => 'The Present Contact:mobile no has already been taken.',
            'per_mobile_no.unique'        => 'The Permanent Contact:mobile no has already been taken.',
            'crw_rank_id.exists'          => 'The Rank does not exists.',
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
