<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVesselBunkerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'                 => ['required'],
            'type'                          => ['nullable', 'string', 'max:255'],
            'usage_type'                    => ['nullable', 'string', 'max:255'],
            'currency'                      => ['nullable', 'string', 'max:255'],
            'exchange_rate_bdt'             => ['nullable', 'numeric'],
            'exchange_rate_usd'             => ['nullable', 'numeric'],
            'total_amount_bdt'              => ['nullable', 'numeric'],
            'total_amount_usd'              => ['nullable', 'numeric'],
            'date'                          => ['required', 'date'],
            'from_date'                     => ['nullable', 'date'],
            'till_date'                     => ['nullable', 'date'],
            'opsBunkers.*.scm_material_id'  => ['nullable', 'numeric', 'max:255'],
            'opsBunkers.*.unit'             => ['nullable', 'string', 'max:255'],
            'opsBunkers.*.quantity'         => ['nullable', 'numeric'],
            'opsBunkers.*.rate'             => ['nullable', 'numeric'],
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
