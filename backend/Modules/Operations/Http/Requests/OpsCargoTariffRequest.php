<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OpsCargoTariffRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            'tariff_name'       => ['required', 'string', 'max:255',Rule::unique('ops_cargo_tariffs')->ignore($this->route('cargo_tariff'), 'id')],
            'ops_vessel_id'     => ['required','exists:ops_vessels,id'],
            'loading_point'     => ['required', 'string', 'max:255'],
            'unloading_point'   => ['required', 'string', 'max:255','different:loading_point'],
            'ops_cargo_type_id' => ['required','exists:ops_cargo_types,id'],
            'currency'          => ['required', 'string', 'max:255'],
            'status'            => ['required', 'string', 'max:50'],
            'business_unit'     => ['required', 'string', 'max:255'],
            'opsCargoTariffLines.*.particular'     => ['required', 'string', 'max:255'],
            'opsCargoTariffLines.*.unit'     => ['required', 'string', 'max:255'],
            'opsCargoTariffLines.*.jan'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.feb'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.mar'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.apr'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.may'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.jun'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.jul'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.aug'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.sep'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.oct'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.nov'     => ['nullable', 'numeric'],
            'opsCargoTariffLines.*.dec'     => ['nullable', 'numeric'],

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
            'tariff_name.unique' => 'Tariff name is already taken.',
            'tariff_name.required' => 'Tariff name is required.',
            'tariff_name.max' => 'Tariff name may not be greater than :max characters.',
            'ops_vessel_id.required' => 'Vessel is required.',
            'ops_vessel_id.exists' => 'Vessel is not valid.',
            'ops_cargo_type_id.required' => 'Cargo type is required.',
            'ops_cargo_type_id.exists' => 'Cargo type is not valid.',
            'loading_point.required' => 'Loading point is required.',
            'loading_point.max' => 'Loading point may not be greater than :max characters.',
            'unloading_point.required' => 'Unloading point is required.',
            'unloading_point.max' => 'Unloading point may not be greater than :max characters.',
            'currency.required' => 'Currency is required.',
            'currency.max' => 'Currency may not be greater than :max characters.',
            'status.required' => 'Status is required.',
            'business_unit.required' => 'Business unit is required.',

            'opsCargoTariffLines.*.particular.required' => 'Particulars is required for row is :position.',
            'opsCargoTariffLines.*.particular.max' => 'Particulars may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.unit.required' => 'Unit unit is required for row is :position.',
            'opsCargoTariffLines.*.unit.max' => 'Unit may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.jan.max' => 'Januery may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.feb.max' => 'February may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.mar.max' => 'March may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.apr.max' => 'April may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.may.max' => 'May may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.jun.max' => 'June may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.jul.max' => 'July may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.aug.max' => 'Augest may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.sep.max' => 'September may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.oct.max' => 'October may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.nov.max' => 'November may not be greater than :max characters for row is :position.',
            'opsCargoTariffLines.*.dec.max' => 'December may not be greater than :max characters for row is :position.',
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
