<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsLighterNoonReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'         => ['required','exists:ops_vessels,id'],
            'ops_voyage_id'         => ['nullable','exists:ops_voyages,id'],
            'ship_master'           => ['required', 'string', 'max:255'],
            'chief_engineer'        => ['required', 'string', 'max:255'],
            'noon_position'         => ['required', 'string', 'max:255'],
            'lat_long'              => ['required', 'string', 'max:255'],
            'status'                => ['nullable', 'string', 'max:255'],
            'engine_running_hours'  => ['nullable', 'max:255'],
            'date'                  => ['required', 'date'],
            'last_port'             => ['required', 'string', 'max:255'],
            'next_port'             => ['required', 'string', 'max:255'],
            'business_unit'         => ['required', 'string', 'max:255'],
            'remarks'               => ['nullable','max:500'],
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
            'ops_vessel_id.required' => 'Vessel is required.',
            'ops_vessel_id.exists' => 'Vessel is not valid.',
            'ops_voyage_id.exists' => 'Voyage is not valid.',
            'ship_master.required' => 'Ship master is required.',
            'ship_master.max' =>  'Ship master may not be greater than :max characters.',
            'chief_engineer.required' => 'Chief engineer is required.',
            'chief_engineer.max' =>  'Chief engineer may not be greater than :max characters.',
            'noon_position.required' => 'Noon position is required.',
            'noon_position.max' =>  'Noon position may not be greater than :max characters.',
            'lat_long.required' => 'Lat/Long is required.',
            'lat_long.max' =>  'Lat/Long may not be greater than :max characters.',
            'engine_running_hours.max' => 'Engine running hours is required.',
            'date.max' => 'Date is required.',
            'date.date' => 'Date must be a date.',
            'last_port.max' => 'Last Port is required.',
            'next_port.max' => 'Next Port is required.',
            'remarks.max' => 'Remarks is required.',
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
