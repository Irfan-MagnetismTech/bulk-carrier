<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsVesselRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    

        /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this);
        return [
            'name'            => ['required', 'string', 'max:255', Rule::unique('ops_vessels')->ignore($this->route('vessel'), 'id')],
            'vessel_type'     => ['required', 'string', 'max:255'],
            'short_code'      => ['required', 'string', 'max:255', Rule::unique('ops_vessels')->ignore($this->route('vessel'), 'id')],
            'call_sign'       => ['required', 'string', 'max:50', Rule::unique('ops_vessels')->ignore($this->route('vessel'), 'id')],
            'owner_name'      => ['required', 'string', 'max:255'],
            'manager'         => ['required', 'string', 'max:255'],
            'classification'  => ['required', 'alpha', 'max:50'],
            'flag'            => ['required', 'string', 'max:50'],
            'port_of_registry'=> ['required', 'string', 'max:255'],
            'nrt'             => ['required', 'integer', 'min:0', 'max:10000000'],
            'dwt'             => ['required'],
            'imo'             => ['required', 'numeric', 'digits_between:10,15'],
            'grt'             => ['required', 'integer', 'min:0', 'max:10000000'],
            'official_number' => ['required', 'numeric', 'digits_between:10,15'],
            'keel_laying_date'=> ['required'],
            'launching_date'  => ['required'],
            'mmsi'            => ['required'],
            'overall_length'  => ['required'],
            'overall_width'   => ['required'],
            'year_built'      => ['required', 'integer', 'min:1900', 'max:3000'],
            'capacity'        => ['required', 'integer', 'min:0', 'max:10000000'],
            'total_cargo_hold'=> ['required', 'numeric', 'max:10000000'],
            'live_tracking_config'=> ['nullable', 'string', 'max:5000'],
            'remarks'         => ['nullable', 'string', 'max:5000'],
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
            'name.required' => 'Vessel Name is required',
            'name.unique' => 'Vessel Name is already taken',
            'name.max' => 'Vessel Name may not be greater than :max characters.',
            'short_code.required' => 'Vessel code is required',
            'short_code.unique' => 'Vessel code is already taken',
            'short_code.max' => 'Vessel code may not be greater than :max characters.',
            'call_sign.required' => 'Call sign is required',
            'call_sign.unique' => 'Call sign is already taken',
            'call_sign.max' => 'Call sign may not be greater than :max characters.',
            'owner_name.required' => 'Owner name is required',
            'owner_name.max' => 'Owner name may not be greater than :max characters.',
            'manager.required' => 'Manager is required',
            'manager.max' => 'Manager may not be greater than :max characters.',
            'classification.required' => 'Classification is required',
            'classification.max' => 'Classification may not be greater than :max characters.',
            'vessel_type.required' => 'Vessel type is required',
            'vessel_type.max' => 'Vessel type may not be greater than :max characters.',
            'flag.required' => 'Flag  is required',
            'flag.max' => 'Flag  may not be greater than :max characters.',
            'classification.required' => 'Classification is required',
            'classification.max' => 'Classification may not be greater than :max characters.',
            'port_of_registry.required' => 'Port of registry is required',
            'port_of_registry.max' => 'Port of registry may not be greater than :max characters.',
            'nrt.required' => 'NRT is required',
            'nrt.max' => 'NRT may not be greater than :max characters.',
            'imo.required' => 'IMO number is required',
            'imo.digits_between' => 'IMO number must be between :min and :max characters',
            'official_number.required' => 'Official Number is required',
            'official_number.digits_between' => 'Official Number must be between :min and :max characters',
            'grt.required' => 'GRT is required',
            'grt.min' => 'GRT must be greater than or equal to :min',
            'grt.max' => 'GRT may not be greater than :max characters.',
            'keel_laying_date.required' => 'Keel Laying Date is required',
            'launching_date.required' => 'Launching Date is required',
            'mmsi.required' => 'MMSI is required',
            'overall_length.required' => 'Length Overall is required',
            'overall_width.required' => 'Width Overall is required',
            'year_built.required' => 'Year built is required',
            'year_built.integer' => 'Year built must be an integer',
            'year_built.min' => 'Year built must be greater than or equal to :min',
            'year_built.max' => 'Year built may not be greater than :max characters.',
            'capacity.required' => 'Capacity is required',
            // 'capacity.integer' => 'Capacity must be an integer',
            'capacity.min' => 'Capacity must be greater than or equal to :min',
            'capacity.max' => 'Capacity may not be greater than :max characters.',
            'total_cargo_hold.required' => 'Total cargo hold is required',
            'total_cargo_hold.max' => 'Total cargo hold may not be greater than :max characters.',
            'live_tracking_config.max' => 'Live tracking may not be greater than :max characters.',
            'remarks.max' => 'Remarks may not be greater than :max characters.',
           
        ];
    }
}
