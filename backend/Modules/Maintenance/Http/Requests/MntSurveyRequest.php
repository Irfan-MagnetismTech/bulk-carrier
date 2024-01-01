<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntSurveyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'mnt_survey_item_id' => 'required',
            'mnt_survey_type_id' => ['required', Rule::unique('mnt_surveys')->where('mnt_survey_item_id', $this->mnt_survey_item_id)->ignore($this->route('survey'), 'id')],
            // 'mnt_survey_type_id' => 'required',
            'short_code' => ['required', Rule::unique('mnt_surveys')->ignore($this->route('survey'), 'id')],
            'survey_name' => ['required', Rule::unique('mnt_surveys')->ignore($this->route('survey'), 'id')]
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
            'mnt_survey_type_id.unique' => 'Survey for selected items on selected type already exists.',
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
