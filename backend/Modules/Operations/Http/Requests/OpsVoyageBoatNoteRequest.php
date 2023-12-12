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
            'ops_voyage_id'      => ['required', Rule::unique('ops_voyage_boat_notes')->ignore($this->route('voyage_boat_note'), 'id')],
            'ops_vessel_id'      => ['required'],
            'type'               => ['nullable'],
            'vessel_draft'       => ['nullable', 'numeric'],
            'water_density'      => ['nullable', 'numeric'],
            'opsVoyageBoatNoteLines.*.voyage_note_type' =>  ['nullable', 'string'],
            'opsVoyageBoatNoteLines.*.quantity' =>  ['nullable', 'numeric'],
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
            'ops_voyage_id.unique' => 'Voyage is already taken.',
            'ops_vessel_id.required' => 'Vessel is required.',
            'vessel_draft.numeric' => 'Draft must be numeric.',
            'water_density.numeric' => 'Density must be numeric.',
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
