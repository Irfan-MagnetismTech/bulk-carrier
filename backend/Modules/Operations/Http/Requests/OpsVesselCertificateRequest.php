<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsVesselCertificateRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
        $this->replace($mergeData);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id' => ['required','exists:ops_vessels,id'],
            'ops_maritime_certification_id' => ['required', 'exists:ops_maritime_certifications,id'],
            'issue_date' => ['required'],
            'expire_date' => Rule::requiredIf(function () {
                return $this->type != 'Permanent';
            }),
            'attachment' => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
            'status' => ['nullable'],
            'reference_number' => ['required'],
            'created_by' => ['nullable'],
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
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_vessel_id.exists' => 'Vessel is not valid',
            'ops_maritime_certification_id.required' => 'Certification is required',
            'ops_maritime_certification_id.exists' => 'Certification is not valid',
            'issue_date.required' => 'Issue date is required',
            'expire_date.required' => 'Expire date is required',
            'reference_number.required' => 'Reference number is already taken',
            'attachment.mimes' => 'Attachment must be a file allowed types are pdf,doc,docx,jpeg,png.',
            'attachment.max' => 'Attachment should not exceed 2048 kilobytes (2 MB).',
           
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
