<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntShipDepartmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'max:30', Rule::unique('mnt_ship_departments')->where('business_unit', $this->business_unit)->ignore($this->id)],
            'short_code' => ['required', 'max:5', Rule::unique('mnt_ship_departments')->where('business_unit', $this->business_unit)->ignore($this->id)],
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
            'name.unique' => 'Ship department name already exists.',
            'short_code.unique' => 'Ship department short code already exists.',
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
