<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsCargoTariffRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // dd(count($this->opsCargoTariffLines));
        return [
            'tariff_name'       => ['required', 'string', 'max:255'],
            'ops_vessel_id'     => ['required', 'numeric', 'max:50'],
            'loading_point'     => ['required', 'string', 'max:255'],
            'unloading_point'   => ['required', 'string', 'max:255'],
            'ops_cargo_type_id' => ['required', 'numeric', 'max:50'],
            'currency'          => ['required', 'string', 'max:255'],
            'status'            => ['required', 'string', 'max:50'],
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
            'tariff_name.required' => 'Tariff name is required',
            'tariff_name.max' => 'Tariff name may not be greater than :max characters.',
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_cargo_type_id.required' => 'Cargo type is required',
            'loading_point.required' => 'Loading point is required',
            'loading_point.max' => 'Loading point may not be greater than :max characters.',
            'unloading_point.required' => 'Unloading point is required',
            'unloading_point.max' => 'Unloading point may not be greater than :max characters.',
            'currency.required' => 'Currency is required',
            'currency.max' => 'Currency may not be greater than :max characters.',
            'status.required' => 'Status is required',
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
