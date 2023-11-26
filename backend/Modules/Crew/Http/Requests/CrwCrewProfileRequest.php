<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwCrewProfileRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data      = request('data');
        $dataArray = json_decode($data, true);

        $mergeData = array_merge($dataArray, [
            'picture' => request('picture'),
            'attachment' => request('attachment')
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
            'crw_recruitment_approval_id'    => 'numeric',
            'agency_id'                      => 'nullable|numeric',
            'department_name'                => 'required|string|max:50',
            'crw_rank_id'                    => 'numeric',
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
            'passport_issue_date'            => 'date',
            'blood_group'                    => 'string|max:255',
            'height'                         => 'string|max:255',
            'weight'                         => 'string|max:255',
            'pre_address'                    => 'required|string|max:255',
            'pre_city'                       => 'required|string|max:255',
            'pre_mobile_no'                  => 'required|string|max:255',
            'pre_email'                      => 'email|max:255',
            'per_address'                    => 'required|string|max:255',
            'per_city'                       => 'required|string|max:255',
            'per_mobile_no'                  => 'required|string|max:255',
            'per_email'                      => 'email|max:255',
            'picture'                        => 'nullable|image|mimes:jpeg,png,gif,svg|max:2048',
            'attachment'                     => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif|max:2048',
            'business_unit'                  => 'required|in:PSML,TSLL',

            // Validation rules for educations_array
            'educations.*.exam_title'        => 'required|string|max:255',
            'educations.*.major'             => 'required|string|max:255',
            'educations.*.institute'         => 'required|string|max:255',
            'educations.*.result'            => 'required|string|max:255',
            'educations.*.passing_year'      => 'required|date_format:Y',
            'educations.*.duration'          => 'string|max:255',
            'educations.*.achievement'       => 'string|max:255',

            // Validation rules for trainings_array
            'trainings.*.training_title'     => 'required|string|max:255',
            'trainings.*.covered_topic'      => 'required|string|max:255',
            'trainings.*.year'               => 'required|date_format:Y',
            'trainings.*.institute'          => 'required|string|max:255',
            'trainings.*.duration'           => 'string|max:255',
            'trainings.*.location'           => 'string|max:255',

            // Validation rules for experiences_array
            'experiences.*.employer_name'    => 'required|string|max:255',
            'experiences.*.from_date'        => 'required|date',
            'experiences.*.till_date'        => 'required|date',
            'experiences.*.last_designation' => 'required|string|max:255',
            'experiences.*.reason_for_leave' => 'string|max:255',

            // Validation rules for languages_array
            'languages.*.language_name'      => 'required|string|max:255',
            'languages.*.writing'            => 'required|string|max:255',
            'languages.*.reading'            => 'required|string|max:255',
            'languages.*.speaking'           => 'required|string|max:255',
            'languages.*.listening'          => 'required|string|max:255',

            // Validation rules for references_array
            'references.*.name'              => 'required|string|max:255',
            'references.*.organization'      => 'required|string|max:255',
            'references.*.designation'       => 'required|string|max:255',
            'references.*.address'           => 'required|string|max:255',
            'references.*.contact_personal'  => 'required|string|max:255',
            'references.*.contact_office'    => 'string|max:255',
            'references.*.email'             => 'email|max:255',
            'references.*.relation'          => 'required|string|max:255',

            // Validation rules for nominees_array
            'nominees.*.name'                => 'required|string|max:255',
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
