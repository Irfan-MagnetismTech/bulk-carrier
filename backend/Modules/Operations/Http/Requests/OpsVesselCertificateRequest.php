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
            'ops_vessel_id' => ['required', 'numeric', 'max:50', Rule::unique('ops_vessel_certificates')->ignore($this->route('vessel_certificate'), 'id')],
            'ops_maritime_certification_id' => ['required', 'numeric', 'max:50'],
            'issue_date' => ['required'],
            'expire_date' => Rule::requiredIf(function () {
                return $this->type != 'Permanent';
            }),
            'attachment' => ['nullable'],
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
            'ops_maritime_certification_id.required' => 'Certification is required',
            'issue_date.required' => 'Issue date is required',
            'expire_date.required' => 'Expire date is required',
            'reference_number.required' => 'Reference number is already taken',
           
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
