<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'classification'  => ['required', 'string', 'max:50'],
            'flag'            => ['required', 'string', 'max:50'],
            'port_of_registry'=> ['required', 'string', 'max:255'],
            'nrt'             => ['required', 'numeric'],
            'dwt'             => ['required','numeric'],
            'imo'             => ['required', 'numeric', 'digits_between:10,15',Rule::unique('ops_vessels')->ignore($this->route('vessel'), 'id')],
            'grt'             => ['required', 'numeric'],
            'official_number' => ['required', 'numeric', 'digits_between:10,15',Rule::unique('ops_vessels')->ignore($this->route('vessel'), 'id')],
            'keel_laying_date'=> ['required'],
            'launching_date'  => ['required'],
            'mmsi'            => ['required',Rule::unique('ops_vessels')->ignore($this->route('vessel'), 'id')],
            'overall_length'  => ['required'],
            'overall_width'   => ['required'],
            'year_built'      => ['required', 'numeric', 'min:1901', 'max:2155'],
            'capacity'        => ['required', 'numeric'],
            'total_cargo_hold'=> ['required', 'numeric'],
            'live_tracking_config'=> ['nullable', 'string', 'max:5000'],
            'dry_docking_months' => ['required', 'numeric'],
            'status' => ['required'],
            'remarks'         => ['nullable', 'string', 'max:500'],
            'opsVesselCertificates.*.ops_maritime_certification_id' => ['nullable', 'numeric', 'max:255', 'distinct'],           
            'opsBunkers.*.scm_material_id' => ['nullable', 'numeric', 'max:255', 'distinct'],
            'opsBunkers.*.unit' => ['nullable', 'string', 'max:255'],
            // 'opsBunkers.*.opening_balance' => ['nullable', 'numeric'],
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
            'imo.unique' => 'IMO is already taken.',
            'imo.required' => 'IMO number is required',
            'imo.digits_between' => 'IMO number must be between :min and :max characters',
            'official_number.unique' => 'Official Number is already taken.',
            'official_number.required' => 'Official Number is required',
            'official_number.digits_between' => 'Official Number must be between :min and :max characters',
            'grt.required' => 'GRT is required',
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
            'total_cargo_hold.required' => 'Total cargo hold is required',
            'live_tracking_config.max' => 'Live tracking may not be greater than :max characters.',
            'remarks.max' => 'Remarks may not be greater than :max characters.',

            'opsVesselCertificates.*.ops_maritime_certification_id.distinct' => 'Certificate cannot be duplicate. Error encountered in row :position',
            'opsBunkers.*.scm_material_id.distinct' => 'Bunker cannot be duplicate. Error encountered in row :position.',
            // 'opsBunkers.*.unit.max' => 'Unit not be greater than :max characters for row is :position.',            
            // 'opsBunkers.*.opening_balance.integer' => 'Opening balance must be an integer for row is :position.',
            // 'opsBunkers.*.opening_balance.max' => 'Opening balance must not exceed :max for row is :position.',
           
        ];
    }
}
