<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwCrewDocumentRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data      = request('data');
        $dataArray = json_decode($data, true);
    
        $mergeData = array_merge($dataArray, ['attachment' => request('attachment')]);
    
        $this->replace($mergeData);
    }    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'document_name'            => 'required|string|max:255',
            'issuing_authority'        => 'required|string|max:255',
            'validity_period'          => 'required|string|max:255',
            'validity_period_in_month' => 'required|numeric|min:0',
            'business_unit'            => 'required|in:PSML,TSLL',

            //for document renewal
            // 'issue_date'               => 'nullable|date', // Validation for issue_date field
            // 'expire_date'              => 'nullable|date',
            // 'reference_no'             => 'nullable|string|max:255',
            // 'attachment'               => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif|max:2048',
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
