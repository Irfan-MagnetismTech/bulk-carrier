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
            'date_time'         => ['required', 'string'],
            'gtm_time'          => ['required', 'string'],
            'location'          => ['required', 'string'],
            'latitude'          => ['required', 'string'],
            'longitude'         => ['required', 'string'],
            'fuel_figures_from'     => ['required', 'string'],
            'fw_last_day_noon_rob'  => ['required', 'string'],
            'fw_production'         => ['required', 'string'],
            'fw_consumption'        => ['required', 'string'],
            'fw_today_noon_rob'     => ['required', 'string'],
            'remarks'               => ['nullable', 'string'],
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
