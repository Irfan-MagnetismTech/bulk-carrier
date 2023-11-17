<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpsPortRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {        
        return [
            'code'  => ['required','string', 'max:255', Rule::unique('ops_ports')->ignore($this->route('port'), 'id')],
            'name'  => ['required', 'string', 'max:255', Rule::unique('ops_ports')->ignore($this->route('port'), 'id')],
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
            'name.required' => 'Port Name is required',
            'name.unique' => 'Port Name is already taken',
            'name.max' => 'Port Name may not be greater than :max characters.',
            'code.required' => 'Port code is required',
            'code.unique' => 'Port code is already taken',
            'code.max' => 'Port code may not be greater than :max characters.',
        ];
    }


}
