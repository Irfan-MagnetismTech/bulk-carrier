<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsMaritimeCertificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this);
        return [
            'name'  => ['required', 'string', 'max:255',Rule::unique('ops_maritime_certifications')->ignore($this->route('maritime_certification'), 'id')],
            'type'  => ['required', 'string', 'max:255'],
            'validity'  => ['required', 'string', 'max:50'],
            'authority'  => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
