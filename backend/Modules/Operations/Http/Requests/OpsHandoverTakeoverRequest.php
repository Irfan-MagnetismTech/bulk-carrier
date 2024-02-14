<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsHandoverTakeoverRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'             => ['required','exists:ops_vessels,id'],
            'ops_charterer_profile_id'  => ['required','exists:ops_charterer_profiles,id'],
            'note_type'                 => ['required', 'string', 'max:255'],
            'effective_date'            => ['required', 'string', 'max:255'],
            // 'exchange_rate'             => ['required', 'numeric'],
            'currency'                  => ['required', 'string', 'max:255'],
            'remarks'                  => ['nullable', 'string', 'max:255'],
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
            'ops_vessel_id.exists' => 'Vessel is not valid.',
            'ops_charterer_profile_id.required' => 'Charterer is required.',
            'ops_charterer_profile_id.exists' => 'Charterer is not valid.',
            'note_type.required' => 'Note type is required.',
            'note_type.max' => 'Note type may not be greater than :max characters.',
            'effective_date.required' => 'Effective date is required.',
            // 'exchange_rate.required' => 'Exchange rate is required.',
            // 'exchange_rate.numeric' => 'Exchange rate is numeric.',
            'currency.required' => 'Currency is required.',
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
