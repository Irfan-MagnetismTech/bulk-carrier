<?php

namespace Modules\Administration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
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
            'name' => ["required", "string", Rule::unique('permissions')->ignore($this->permission)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.unique'   => 'Name already exists.',
        ];
    }
}
