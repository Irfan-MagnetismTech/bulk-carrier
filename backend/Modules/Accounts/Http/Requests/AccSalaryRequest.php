<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccSalaryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'acc_cost_center_id'          => 'nullable|numeric',
            'year_month'                  => ['required', Rule::unique('acc_salaries')->ignore($this->id)],
            'total_salary'                => 'required|numeric|min:0',
            'remarks'                     => 'nullable|string|max:500',
            'business_unit'               => 'required|in:PSML,TSLL',
            'accSalaryLines.*.particular' => 'required|string|max:255',
            'accSalaryLines.*.amount'     => 'required|numeric|min:0|max:999999999999.99'
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
