<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'mnt_item_group_id' => 'required',
            'name'  => ['required', Rule::unique('mnt_items')->where('business_unit', $this->business_unit)->ignore($this->id)],
            'item_code' => ['required', Rule::unique('mnt_items')->where('business_unit', $this->business_unit)->ignore($this->id)],
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
            'name.unique' => 'Item name on selected business unit already exists.',
            'item_code.unique' => 'Item code on selected business unit already exists.',
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
