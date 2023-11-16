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
            'ops_vessel_id'             => ['required'],
            'ops_charterer_profile_id'  => ['required'],
            'note_type'                 => ['required', 'string', 'max:255'],
            'effective_date'            => ['required', 'string', 'max:255'],
            'exchange_rate'             => ['required', 'max:255'],
            'currency'                  => ['required', 'string', 'max:255'],
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
