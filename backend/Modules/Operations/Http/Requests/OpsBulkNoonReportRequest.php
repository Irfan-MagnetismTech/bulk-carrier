<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsBulkNoonReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'     => ['required', 'numeric', 'max:50'],
            'ops_voyage_id'     => ['required', 'numeric', 'max:50'],
            'ship_master'       => ['required', 'string'],
            'chief_engineer'    => ['required', 'string'],
            'wind_condition'    => ['required', 'string'],
            'type'              => ['required', 'string'],
            'date_time'         => ['required', 'datetime'],
            'gtm_time'          => ['required', 'datetime'],
            'location'          => ['required', 'string'],
            'latitude'          => ['required', 'string'],
            'longitude'         => ['required', 'string'],
            'fuel_figures_from'     => ['required', 'string'],
            'fw_last_day_noon_rob'  => ['required', 'string'],
            'fw_production'         => ['required', 'string'],
            'fw_consumption'        => ['required', 'string'],
            'fw_today_noon_rob'     => ['required', 'string'],
            'remarks'               => ['nullable', 'string', 'max:255'],
            'status'                => ['nullable', 'string'],
            'sea_condition'         => ['required', 'string'],
            'business_unit'         => ['required', 'string'],
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
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_voyage_id.required' => 'Voyage is required',
            'ship_master.required' => 'Ship master is required',
            'chief_engineer.required' => 'Chief engineer is required',
            'wind_condition.required' => 'Wind condition is required',
            'type.required' => 'Type is required',
            'date_time.required' => 'Date and Time is required',
            'gtm_time.required' => 'GTM Time is required',
            'location.required' => 'Location is required',
            'latitude.required' => 'Latitude is required',
            'longitude.required' => 'Longitude is required',
            'fuel_figures_from.required' => 'Fuel figures is required',
            'fw_last_day_noon_rob.required' => 'FW last day noon (ROB) is required',
            'remarks.required' => 'Remarks is required',
            'status.required' => 'Status is required',
            'sea_condition.required' => 'Sea condition is required',
            
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
