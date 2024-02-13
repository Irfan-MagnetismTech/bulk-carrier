<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
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
            'ops_vessel_id'     => ['required','exists:ops_vessels,id'],
            'ops_voyage_id'     => ['required','exists:ops_voyages,id'],
            'ship_master'       => ['nullable', 'string'],
            'chief_engineer'    => ['nullable', 'string'],
            'wind_condition'    => ['nullable', 'string'],
            'type'              => ['required', 'string'],
            'date_time'         => ['nullable'],
            'gtm_time'          => ['nullable'],
            'location'          => ['nullable', 'string'],
            'latitude'          => ['nullable', 'string'],
            'longitude'         => ['nullable', 'string'],
            'fuel_figures_from'     => ['nullable', 'string'],
            'fw_last_day_noon_rob'  => ['nullable', 'string'],
            'fw_production'         => ['nullable', 'string'],
            'fw_consumption'        => ['nullable', 'string'],
            'fw_today_noon_rob'     => ['nullable', 'string'],
            'remarks'               => ['nullable', 'string', 'max:500'],
            'status'                => ['nullable', 'string'],
            'sea_condition'         => ['nullable', 'string'],
            'business_unit'         => ['nullable', 'string'],

            // 'opsBulkNoonReportEngineInputs.*.engine_unit'     => ['required', 'string', 'max:255',Rule::unique('ops_bulk_noon_report_engine_inputs', 'engine_unit')
            // ->ignore($this->bulk_noon_report?->opsBulkNoonReportEngineInputs->first()->id, 'id')],
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

            // 'ops_vessel_id.required' => 'Vessel is required.',
            // 'ops_vessel_id.exists' => 'Vessel is not valid.',
            // 'ops_voyage_id.required' => 'Voyage is required.',
            // 'ops_voyage_id.exists' => 'Voyage is not valid.',
            // 'ship_master.required' => 'Ship master is required.',
            // 'chief_engineer.required' => 'Chief engineer is required.',
            // 'wind_condition.required' => 'Wind condition is required.',
            'type.required' => 'Report type is required.',
            // 'date_time.required' => 'Date and Time is required.',
            // 'gtm_time.required' => 'GTM Time is required.',
            // 'location.required' => 'Location is required.',
            // 'latitude.required' => 'Latitude is required.',
            // 'longitude.required' => 'Longitude is required.',
            // 'fuel_figures_from.required' => 'Fuel figures is required.',
            // 'fw_last_day_noon_rob.required' => 'FW last day noon (ROB) is required.',
            // 'remarks.required' => 'Remarks is required.',
            // 'status.required' => 'Status is required.',
            // 'sea_condition.required' => 'Sea condition is required.',
            
            'remarks.max' => 'Remarks may not be greater than :max characters.',
            // 'opsBulkNoonReportEngineInputs.*.engine_unit.unique' => 'Engine Unit is already take for row is :position.',
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
