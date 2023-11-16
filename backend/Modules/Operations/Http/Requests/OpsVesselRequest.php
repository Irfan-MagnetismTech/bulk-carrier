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
            'imo'             => ['required', 'numeric', function ($attribute, $value, $fail) {
                if (strlen((string) $value) < 10) {
                    $fail('The ' . $attribute . ' must be at least 10 characters.');
                }
            }],
            'grt'             => ['required', 'integer', 'min:0', 'max:10000000'],
            'official_number' => ['required', 'numeric', function ($attribute, $value, $fail) {
                if (strlen((string) $value) < 10) {
                    $fail('The ' . $attribute . ' must be at least 10 characters.');
                }
            }],
            'keel_laying_date'=> ['required'],
            'launching_date'  => ['required'],
            'mmsi'            => ['required'],
            'overall_length'  => ['required'],
            'overall_width'   => ['required'],
            'year_built'      => ['required', 'integer', 'min:1900', 'max:3000'],
            'capacity'        => ['required', 'numeric', 'min:0', 'max:10000000'],
            'total_cargo_hold'=> ['required', 'numeric', 'max:255'],
            'live_tracking_config'=> ['nullable', 'string', 'max:5000'],
            'remarks'         => ['nullable', 'string', 'max:5000'],
        ];
    }
}
