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
            'voyage_no' 	        => ['required'],
            'voyage_sequence' 	    => ['required'],
            'route'                 => ['required'],
            'load_port_distance'    => ['nullable'],
            'sail_date'             => 'required|date',
            'transit_date'          => 'required|date',
            'remarks'               => ['nullable'],
            'business_unit'         => ['required'],
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
            'ops_customer_id.required' => 'Customer Name is required',
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_cargo_type_id.required' => 'Cargo Type is required',    
            'voyage_no.required' => 'Voyage No. is required',
            'route.required' => 'Route is required',
            'route.max' => 'Route may not be greater than :max characters.',
            'load_port_distance.required' => 'Load Port Distance (NM) is required',
            'load_port_distance.max' => 'Load Port Distance (NM) may not be greater than :max characters.',
            'sail_date.required' => 'Sail Date is required',
            'transit_date.required' => 'Transit Date is required',
            'remarks.max' => 'Remarks may not be greater than :max characters.',
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
