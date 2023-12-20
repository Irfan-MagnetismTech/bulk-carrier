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
            'ops_vessel_id'     => ['required', 'numeric', 'max:50'],
            'ops_voyage_id'     => ['required', 'numeric', 'max:50'],
            'created_by'        => ['nullable', 'numeric', 'max:50'],
            'requisition_no'    => ['required', 'string','max:50','unique:ops_bunker_requisitions,requisition_no,'.$this->id],
            'remarks'           => ['nullable', 'string'],
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
            'ops_voyage_id.required' => 'Voyage is required',
            'requisition_no.required' => 'Requisiton No is required',
            'requisition_no.max' => 'Requisiton No may not be greater than :max characters.',
            'requisition_no.unique' => 'This Requisiton No is already exist.',
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
