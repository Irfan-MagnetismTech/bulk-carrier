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
