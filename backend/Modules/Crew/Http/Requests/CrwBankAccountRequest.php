<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwBankAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {

        return [
            'crw_crew_id'      => 'required|exists:related_table,id',
            'bank_name'        => 'required|string|max:255',
            'branch_name'      => 'required|string|max:255',
            'routing_number'   => 'string|max:255',
            'account_name'     => 'required|string|max:255',
            'account_number'   => 'required|string|max:255',
            'benificiary_name' => 'required|string|max:255',
            'attachment'       => 'required|string|max:255',
            'is_active'        => 'required|boolean',
            'business_unit'    => 'required|in:PSML,TSLL',
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
