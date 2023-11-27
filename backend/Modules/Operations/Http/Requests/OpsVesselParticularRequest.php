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
            'vessel_type'       => ['required', 'string'],
            'class_no'          => ['required', 'string'],
            'loa'               => ['required', 'numeric'],
            'depth'             => ['required', 'numeric'],
            'ecdis_type'        => ['required', 'string'],
            'maker_ssas'        => ['required', 'string'],
            'engine_type'       => ['required', 'string'],
            'bhp'               => ['required', 'numeric'],
            'email'             => ['required', 'email'],
            'lbc'               => ['required', 'string'],
            'previous_name'     => ['string'],
            'call_sign'         => ['required','max:50', Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'owner_name'        => ['required', 'string'],
            'classification'    => ['required', 'alpha', 'max:50'],
            'flag'              => ['required', 'string', 'max:50'],
            'previous_flag'     => ['string', 'max:50'],
            'port_of_registry'  => ['required', 'string'],
            'nrt'               => ['required', 'numeric'],
            'dwt'               => ['required'],
            'imo'               => ['required', 'numeric', 'digits_between:10,15'],
            'grt'               => ['required', 'numeric'],
            'official_number'   => ['required', 'numeric', 'digits_between:10,15'],
            'keel_laying_date'  => ['required'],
            'year_built'        => ['required', 'numeric', 'min:1900', 'max:3000'],
            'capacity'          => ['required', 'numeric'],
            'tues_capacity'     => ['required', 'numeric'],
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
            'ops_vessel_id.required' => 'Vessel is required.',
            'vessel_type.required' => 'Vessel type is required.',
            'issue_date.required' => 'Owner Name  is required.',
            'depth.required' => 'Depth is required.',
            'depth.numeric' => 'Depth must be numeric.',
            'class_no.required' => 'Class No  is required.',
            'loa.required' => 'LOA  is required.',
            'loa.numeric' => 'LOA must be numeric.',
            'ecdis_type.required' => 'ECDIS type  is required.',
            'maker_ssas.required' => 'Maker SSAS  is required.',
            'engine_type.required' => 'Engine type  is required.',
            'bhp.required' => 'BHP  is required.',
            'bhp.numeric' => 'BHP must be numeric.',
            'email.required' => 'Email  is required.',
            'lbc.required' => 'LBC  is required.',
            'call_sign.required' => 'Call Sign  is required.',
            'call_sign.unique' => 'Call Sign is already taken',
            'classification.required' => 'Classification  is required.',
            'flag.required' => 'Flag  is required.',
            'port_of_registry.required' => 'Port of Registry  is required.',
            'nrt.required' => 'NRT  is required.',
            'dwt.required' => 'DWT  is required.',
            'imo.required' => 'IMO number is required',
            'imo.digits_between' => 'IMO number must be between :min and :max characters',
            'official_number.required' => 'Official Number is required',
            'official_number.digits_between' => 'Official Number must be between :min and :max characters',
            'grt.required' => 'GRT is required',
            'keel_laying_date.required' => 'Keel Laying Date is required',
            'year_built.required' => 'Year built is required',
            'year_built.integer' => 'Year built must be an integer',
            'year_built.min' => 'Year built must be greater than or equal to :min',
            'year_built.max' => 'Year built may not be greater than :max characters.',
            'capacity.required' => 'Capacity is required',
            'tues_capacity.required' => 'Tues Capacity is required',
            'mmsi.required' => 'MMSI is required',
            'overall_length.required' => 'Length Overall is required',
            'overall_width.required' => 'Width Overall is required',
            'depth_moulded.required' => 'Depth (Moulded) is required',
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
