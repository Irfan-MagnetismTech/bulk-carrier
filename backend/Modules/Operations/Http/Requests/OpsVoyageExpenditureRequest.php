<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageExpenditureRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'voyage_id'         => ['required', 'numeric', 'max:20'],
            'port'              => ['required', 'string', 'max:255'],
            'currency'          => ['required', 'string', 'max:255'],
            'rate'              => ['required', 'numeric'],
            'total_usd'         => ['required', 'numeric'],
            'total_bdt'         => ['required', 'numeric'],
            'expense_json'      => ['required', 'string'],
            'date'              => ['required', 'string', 'max:255'],
            'type'              => ['required', 'string', 'max:255'],
            'business_unit'     => ['required', 'string', 'max:255'],
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
