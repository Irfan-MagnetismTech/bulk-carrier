<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsLighterNoonReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'         => ['required', 'max:10'],
            'ops_voyage_id'         => ['required', 'max:10'],
            'ship_master'           => ['required', 'string', 'max:255'],
            'chief_engineer'        => ['required', 'string', 'max:255'],
            'noon_position'         => ['required', 'string', 'max:255'],
            'status'                => ['nullable', 'string', 'max:255'],
            'engine_running_hours'  => ['nullable', 'max:255'],
            'date'                  => ['required', 'date', 'max:255'],
            'last_port'             => ['required', 'string', 'max:255'],
            'next_port'             => ['required', 'string', 'max:255'],
            'business_unit'         => ['required', 'string', 'max:255'],
            'remarks'               => ['required', 'string', 'max:1000'],
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
