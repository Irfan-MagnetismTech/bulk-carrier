<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_customer_id'       => ['required'],
            'ops_vessel_id'         => ['required'],
            'mother_vessel'         => ['nullable'],
            'ops_cargo_type_id'     => ['required'],
            'voyage_sequence'       => ['required', 'string'],
            'voyage_no' 	        => ['required'],
            'route'                 => ['required', 'string', 'max:255'],
            'load_port_distance'    => ['required', 'string', 'max:255'],
            'sail_date'             => 'required|date',
            'transit_date'          => 'required|date',
            'remarks'               => ['nullable', 'string'],
            'status'               => ['nullable', 'string'],
            'business_unit'         => ['required', 'string', 'max:255'],
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
