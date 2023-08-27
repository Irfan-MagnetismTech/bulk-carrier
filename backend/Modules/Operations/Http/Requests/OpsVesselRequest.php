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
            'name'            => ['required', 'string', 'max:255', Rule::unique('ops_vessels')->ignore($this->vessel)],
            'code'            => ['required', 'string', 'max:255', Rule::unique('ops_vessels')->ignore($this->vessel)],
            'ownership'       => ['required', 'string', 'max:50'],
            'flag'            => ['required', 'string', 'max:50'],
            'year_built'      => ['required', 'integer', 'min:1900', 'max:3000'],
            'call_sign'       => ['required', 'alpha_num', 'max:50', Rule::unique('ops_vessels')->ignore($this->vessel)],
            'grt'             => ['required', 'integer', 'min:0', 'max:10000000'],
            'nrt'             => ['required', 'integer', 'min:0', 'max:10000000'],
            'dwt'             => ['required'],
            'speed'           => ['required'],
            'capacity_tues'   => ['required', 'integer', 'min:0', 'max:10000000'],
            'reefer_capacity' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'imo_number'      => ['required', 'alpha_num', 'max:50'],
            'classification'  => ['required', 'alpha', 'max:50'],
            'remarks'         => ['nullable', 'string', 'max:5000'],
        ];
    }
}
