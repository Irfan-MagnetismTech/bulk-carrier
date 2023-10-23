<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVesselCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id' => ['required', 'numeric', 'max:50'],
            'ops_maritime_certification_id' => ['required', 'numeric', 'max:50'],
            'issue_date' => ['required'],
            'expire_date' => ['required'],
            'attachment' => ['required'],
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
