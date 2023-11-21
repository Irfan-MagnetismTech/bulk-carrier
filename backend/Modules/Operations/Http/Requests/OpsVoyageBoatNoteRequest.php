<?php

namespace Modules\Operations\Http\Requests;

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
            'ops_voyage_id'      => ['required'],
            'ops_vessel_id'      => ['required'],
            'type'               => ['nullable'],
            'vessel_draft'       => ['nullable', 'string'],
            'water_density'      => ['nullable', 'string'],
            'opsVoyageBoatNoteLines.*.voyage_note_type.required' =>  ['nullable', 'string'],
            'opsVoyageBoatNoteLines.*.quantity.required' =>  ['nullable', 'integer'],
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
            'ops_vessel_id.required' => 'Vessel is required.',
            'opsVoyageBoatNoteLines.*.voyage_note_type.required' => 'Boat note type is required for row is :position.',
            'opsVoyageBoatNoteLines.*.quantity.required' => 'Quantity is required for row is :position.',
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