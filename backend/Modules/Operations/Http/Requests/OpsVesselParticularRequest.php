<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsVesselParticularRequest extends FormRequest
{
    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
        $this->replace($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [  
            'ops_vessel_id'     => ['required', 'numeric', 'max:20'],
            'vessel_type'       => ['required', 'string', 'max:255'],
            'depth'             => ['required', 'string', 'max:255'],
            'class_no'          => ['required', 'string', 'max:255'],
            'loa'               => ['required', 'numeric', 'max:255'],
            'depth'             => ['required', 'numeric', 'max:255'],
            'ecdis_type'        => ['required', 'string', 'max:255'],
            'maker_ssas'        => ['required', 'string', 'max:255'],
            'engine_type'       => ['required', 'string', 'max:255'],
            'bhp'               => ['required', 'numeric', 'max:255'],
            'email'             => ['required', 'string', 'max:255'],
            'lbc'               => ['required', 'string', 'max:255'],
            'previous_name'     => ['string', 'max:255'],
            'call_sign'         => ['required', 'alpha_num', 'max:50', Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'owner_name'        => ['required', 'string', 'max:255'],
            'classification'    => ['required', 'alpha', 'max:50'],
            'flag'              => ['required', 'string', 'max:50'],
            'previous_flag'     => ['string', 'max:50'],
            'port_of_registry'  => ['required', 'string', 'max:255'],
            'nrt'               => ['required', 'numeric', 'min:0', 'max:10000000'],
            'dwt'               => ['required'],
            'imo'               => ['required', 'alpha_num', 'max:50'],
            'grt'               => ['required', 'numeric', 'min:0', 'max:10000000'],
            'official_number'   => ['required', 'alpha_num', 'max:50'],
            'keel_laying_date'  => ['required'],
            'year_built'        => ['required'],
            'tues_capacity'     => ['required'],
            'mmsi'              => ['required'],
            'overall_length'    => ['required'],
            'overall_width'     => ['required'],
            'depth_moulded'     => ['required'],
            'business_unit'     => ['required'],
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
