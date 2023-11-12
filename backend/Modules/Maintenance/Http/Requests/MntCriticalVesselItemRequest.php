<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MntCriticalVesselItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'business_unit' => 'required',
            'ops_vessel_id' => 'required',
            'mnt_critical_item_id' => ['required', Rule::unique('mnt_critical_vessel_items')->where('ops_vessel_id', $this->ops_vessel_id)->ignore($this->route('critical_vessel_item'), 'id')],
            'mntCriticalItemSps.*.sp_name' => 'required',
            'mntCriticalItemSps.*.unit' => 'required',
            'mntCriticalItemSps.*.min_rob' => 'required|integer|min:1',
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
            'mnt_critical_item_id.unique' => 'The Critical item for selected vessel already exists.',
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
