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
            'ops_vessel_id'     => ['nullable', 'numeric','max:50', Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'short_code'        => ['nullable', 'string','max:20', Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'vessel_type'       => ['nullable', 'string', 'max:255'],
            'class_no'          => ['nullable', 'string', 'max:255'],
            'loa'               => ['nullable', 'numeric'],
            'depth'             => ['nullable', 'numeric'],
            'ecdis_type'        => ['nullable', 'string', 'max:255'],
            'maker_ssas'        => ['nullable', 'string', 'max:255'],
            'engine_type'       => ['nullable', 'string', 'max:255'],
            'bhp'               => ['nullable', 'numeric'],
            'email'             => ['nullable', 'email'],
            'lbc'               => ['nullable', 'string', 'max:255'],
            'previous_name'     => ['nullable', 'string', 'max:255'],
            
            'call_sign'         =>['nullable', 'string', 'max:255', Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'attachment'        => 'nullable|mimes:xlsx',
            'owner_name'        => ['nullable', 'string', 'max:255'],
            'classification'    => ['nullable', 'string'],
            'flag'              => ['nullable', 'string', 'max:255'],
            'previous_flag'     => ['nullable', 'string', 'max:255'],
            'port_of_registry'  => ['nullable', 'string', 'max:255'],
            'nrt'               => ['nullable', 'numeric'],
            'imo'               => ['nullable', 'numeric', 'digits_between:10,15',Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'grt'               => ['nullable', 'numeric'],
            'official_number'   => ['nullable', 'numeric', 'digits_between:10,15',Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
            'year_built'        => ['numeric', 'min:1900', 'max:3000'],
            'capacity'          => ['nullable', 'numeric'],
            'tues_capacity'     => ['nullable', 'numeric'],   

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
            'ops_vessel_id.unique' => 'Vessel is already taken.',
            'short_code.unique' => 'Vessel short code is already taken.',
            'depth.numeric' => 'Depth must be numeric.',
            'loa.numeric' => 'LOA must be numeric.',
            'bhp.numeric' => 'BHP must be numeric.',
            'call_sign.unique' => 'Call sign is already taken.',
            'imo.unique' => 'IMO is already taken.',
            'imo.digits_between' => 'IMO number must be between :min and :max characters.',         
            'attachment.mimes' => 'Attachment field must be an excel file of type: xlsx.',
            'official_number.unique' => 'Official number is already taken.',
            'official_number.digits_between' => 'Official Number must be between :min and :max characters.',
            'year_built.integer' => 'Year built must be an integer.',
            'year_built.min' => 'Year built must be greater than or equal to :min.',
            'year_built.max' => 'Year built may not be greater than :max characters.',
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
