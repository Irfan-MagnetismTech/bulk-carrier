<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppraisalFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'form_no'                           => 'required|string|max:255',
            'form_name'                         => 'required|string|max:255',
            'version'                           => 'required|string|max:255',
            'description'                       => 'nullable|string|max:255',
            'appraisalFormLines.*.section_name' => 'required|string|max:255',
            'appraisalFormLines.*.aspect'       => 'required|string|max:255',
            'appraisalFormLines.*.description'  => 'nullable|string|max:255',
            'appraisalFormLines.*.answer_type'  => 'required|in:Number,Boolean,Grade',
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
