<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageBoatNoteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        // dd(request('info'));
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $mergeData = array_merge($dataArray , ['attachment' => request('attachments')]);
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
            'ops_voyage_id'      => ['required','exists:ops_voyages,id', Rule::unique('ops_voyage_boat_notes')->ignore($this->route('voyage_boat_note'), 'id')],
            'ops_vessel_id'      => ['required','exists:ops_vessels,id'],
            'type'               => ['nullable'],
            'vessel_draft'       => ['nullable', 'numeric'],
            'water_density'      => ['nullable', 'numeric'],
            'opsVoyageBoatNoteLines.*.voyage_note_type' =>  ['nullable', 'string'],
            'opsVoyageBoatNoteLines.*.quantity' =>  ['nullable', 'numeric'],
            'opsVoyageBoatNoteLines.*.attachment' =>  'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
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
            'ops_voyage_id.required' => 'Voyage is required.',
            'ops_voyage_id.exists' => 'Voyage is not valid.',
            'ops_voyage_id.unique' => 'Voyage is already taken.',
            'ops_vessel_id.required' => 'Vessel is required.',
            'ops_vessel_id.exists' => 'Vessel is not valid.',
            'vessel_draft.numeric' => 'Draft must be numeric.',
            'water_density.numeric' => 'Density must be numeric.',

            'opsVoyageBoatNoteLines.*.attachment.mimes' => 'Attachment must be an excel file of type: pdf,doc,docx,jpeg,png,gif,xlsx for row is :position.',
            'opsVoyageBoatNoteLines.*.attachment.max' => 'Attachment should not exceed 2048 kilobytes (2 MB) for row is :position.',
            // 'opsVoyageBoatNoteLines.*.voyage_note_type.required' => 'Boat note type is required for row is :position.',
            // 'opsVoyageBoatNoteLines.*.quantity.required' => 'Quantity is required for row is :position.',
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
