<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVesselParticularRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // dd($this);
        return [
            'ops_vessel_id' => ['required'],
            'attachment' => 'nullable|mimes:png,jpeg,jpg,pdf,xlsx,docx,doc|max:2048',
            'class_no' => ['required'],
            'loa' => ['required'],
            'depth' => ['required'],
            'ecdis_type' => ['required'],
            'maker_ssas' => ['required'],
            'engine_type' => ['required'],
            'bhp' => ['required'],
            'email' => 'required|email',
            'lbc' => ['required'],
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
