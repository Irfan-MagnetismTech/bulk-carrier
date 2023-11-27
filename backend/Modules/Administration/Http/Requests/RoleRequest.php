<?php

namespace Modules\Administration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $role = $this->route('role'); // Assuming the route parameter is named 'role'

        return [
            'name' => ['required',Rule::unique('roles')->ignore($role),'max:50'],
            'current_permissions' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The role name is required.',
            'name.unique' => 'The role name has already been taken.',
            'name.max' => 'The role name field must not exceed 50 characters.',
        ];
    }
}
