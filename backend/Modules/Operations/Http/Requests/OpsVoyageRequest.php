<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
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
            // 'ops_customer_id'       => ['required'],
            'ops_vessel_id'         => ['required'],
            'mother_vessel'         => ['nullable'],
            'ops_cargo_type_id'     => ['required'],
            'voyage_no' 	        => ['required',Rule::unique('ops_voyages')->where('ops_vessel_id',$this->ops_vessel_id)->ignore($this->route('voyage'), 'id')],
            'voyage_sequence' 	    => ['required',Rule::unique('ops_voyages')->where('ops_vessel_id',$this->ops_vessel_id)->ignore($this->route('voyage'), 'id')],
            'route'                 => ['required', 'max:255'],
            'load_port_distance'    => ['nullable'],
            'sail_date'             => 'required|date',
            'transit_date'          => 'required|date',
            'remarks'               => ['nullable','max:255'],
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
            'voyage_no.unique' => 'Voyage No. is already taken to this vessel.',
            'voyage_sequence.required' => 'Voyage sequence is required',
            'voyage_sequence.unique' => 'Voyage sequence is already taken to this vessel.',
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
