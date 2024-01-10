<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmMoRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data =  request('data');
        $dataArray = json_decode($data, true);

        // $mergeData = array_merge($dataArray, ['attachment' => request('attachment'), 'excel' => request('excel')]);

        $this->replace($dataArray);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'business_unit' => 'required',
            'scm_mmr_id' => 'required|exists:scm_mmrs,id',
            'date' => 'required|date',
            'scmMoLins.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmMoLins.*.quantity' => 'required|numeric',
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
            'business_unit.required' => 'Business unit is required',

            'scm_mmr_id.required' => 'MMR is required',
            'scm_mmr_id.exists' => 'MMR is not valid',

            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',

            'scmMoLins.*.scm_material_id.required' => 'Material is required',
            'scmMoLins.*.scm_material_id.exists' => 'Material is not valid',
            'scmMoLins.*.scm_material_id.integer' => 'Material is not valid',

            'scmMoLins.*.quantity.required' => 'Quantity is required',
            'scmMoLins.*.quantity.numeric' => 'Quantity must be a number',
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
