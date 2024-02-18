<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwSalaryStructureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_crew_id'        => ['required','integer','exists:crw_crew_profiles,id',
                                        Rule::unique('crw_salary_structures')
                                        ->where('gross_salary', $this->gross_salary)
                                        ->ignore($this->id)],

            'promotion_id'       => 'nullable|integer|exists:promotion_table,id',
            'increment_sequence' => 'nullable|integer',
            'effective_date'     => 'required|date',
            'currency'           => 'required|string|max:255',
            'gross_salary'       => 'numeric|min:0|max:9999999.99|',
            'addition'           => 'numeric|min:0|max:9999999.99|',
            'deduction'          => 'numeric|min:0|max:9999999.99|',
            'net_amount'         => 'numeric|min:0|max:9999999.99|',
            'is_active'          => 'required|boolean',
            'business_unit'      => 'required|in:PSML,TSLL',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crw_crew_id.unique'   => 'A record with the combination of crew name and gross salary already exists.',
            'crw_crew_id.exists'   => 'The Crew Name does not exists.',
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
