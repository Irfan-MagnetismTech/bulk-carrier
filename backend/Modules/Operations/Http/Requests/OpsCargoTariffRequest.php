<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsCargoTariffRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tariff_name'       => ['required', 'string', 'max:255'],
            'ops_vessel_id'     => ['required', 'number', 'max:10'],
            'loading_point'     => ['required', 'string', 'max:255'],
            'unloading_point'   => ['required', 'string', 'max:255'],
            'ops_cargo_type_id' => ['required', 'number', 'max:255'],
            'currency'          => ['required', 'string', 'max:255'],
            'status'            => ['required', 'enum', 'max:5'],
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