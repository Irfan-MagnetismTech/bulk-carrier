<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwSalaryStructureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_crew_id'        => 'required|integer|exists:crw_crew_profiles,id',
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
