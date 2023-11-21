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
            'opsCargoTariffLines.*.particular'     => ['required', 'string', 'max:255'],
            'opsCargoTariffLines.*.unit'     => ['required', 'string', 'max:255'],
            'opsCargoTariffLines.*.jan'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.feb'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.mar'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.apr'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.may'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.jun'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.jul'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.aug'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.sep'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.oct'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.nov'     => ['nullable', 'numeric', 'max:255'],
            'opsCargoTariffLines.*.dec'     => ['nullable', 'numeric', 'max:255'],

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
            'business_unit.required' => 'Business unit is required',

            // 'opsCargoTariffLines.*.particular.required' => 'Particulars is required for row is key:index',
            // 'opsCargoTariffLines.*.particular.max' => 'Particulars may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.unit.required' => 'Unit unit is required for row is key:index',
            // 'opsCargoTariffLines.*.unit.max' => 'Unit may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.jan.max' => 'Januery may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.feb.max' => 'February may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.mar.max' => 'March may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.apr.max' => 'April may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.may.max' => 'May may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.jun.max' => 'June may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.jul.max' => 'July may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.aug.max' => 'Augest may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.sep.max' => 'September may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.oct.max' => 'October may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.nov.max' => 'November may not be greater than :max characters for row is key:index',
            // 'opsCargoTariffLines.*.dec.max' => 'December may not be greater than :max characters for row is key:index',
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


    // public function withValidator($validator)
    // {
    //     $messages= $validator->errors()->messages();
    //     $messages = collect($messages)->map(function ($messageArray, $field) {
    //         $table = Str::before($field, '.');
    //         $index = Str::before(Str::after($field, $table . '.'), '.');
    //         $index = Str::before($index, '.');
        
    //         return collect($messageArray)->map(function ($message) use ($index) {
    //             return Str::before($message, 'key' . $index) . ' ' . ++$index;
    //         })->all();
    //     })->all();

    //     $response= new \Illuminate\Http\JsonResponse([
    //         'message' =>'The given data was invalid',
    //         'errors' => $messages
    //     ], 422);

    //     throw new HttpResponseException($response);
    // }
}
