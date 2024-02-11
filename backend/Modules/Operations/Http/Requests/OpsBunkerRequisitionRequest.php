<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsBunkerRequisitionRequest extends FormRequest
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
            'created_by'        => ['nullable','exists:users,id'],
            'requisition_no'    => ['required', 'string','max:50','unique:ops_bunker_requisitions,requisition_no,'.$this->id],
            'remarks'           => ['nullable', 'string','max:500'],
            'status'            => ['nullable', 'string'],
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
            'ops_vessel_id.exists' => 'Vessel is not valid',
            'ops_voyage_id.required' => 'Voyage is required',
            'ops_voyage_id.exists' => 'Voyage is not valid',
            'created_by.exists' => 'Created By is not valid',
            'requisition_no.required' => 'Requisiton No is required',
            'requisition_no.max' => 'Requisiton No may not be greater than :max characters.',
            'requisition_no.unique' => 'This Requisiton No is already exist.',
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
