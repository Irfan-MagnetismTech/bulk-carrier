<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageBoatNoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_voyage_id'      => ['required', 'numeric', 'max:20'],
            'ops_vessel_id'      => ['required', 'numeric', 'max:20'],
            'type'               => ['required'],
            'vessel_draft'       => ['required', 'string', 'max:255'],
            'water_density'      => ['required', 'string', 'max:255'],
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
