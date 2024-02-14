<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsVesselParticularRequest extends FormRequest
{
    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $imgData = is_object(request('attachment')) ? request('attachment') : null;
        $mergeData = array_merge($dataArray , ['attachment' => $imgData]);
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
            'ops_vessel_id'     => ['nullable','exists:ops_vessels,id', Rule::unique('ops_vessel_particulars')->ignore($this->route('vessel_particular'), 'id')],
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
            'attachment'        =>'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
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
            'ops_vessel_id.exists' => 'Vessel is not valid.',
            'short_code.unique' => 'Vessel short code is already taken.',
            'depth.numeric' => 'Depth must be numeric.',
            'loa.numeric' => 'LOA must be numeric.',
            'bhp.numeric' => 'BHP must be numeric.',
            'call_sign.unique' => 'Call sign is already taken.',
            'email.email' => 'Please enter a valid email.',
            'imo.unique' => 'IMO is already taken.',
            'imo.digits_between' => 'IMO number must be between :min and :max characters.',         
            'attachment.mimes' => 'Attachment must be an excel file of type: pdf,doc,docx,jpeg,png,gif,xlsx.',
            'attachment.max' => 'Attachment should not exceed 2048 kilobytes (2 MB).',
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
