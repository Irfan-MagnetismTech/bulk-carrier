<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmSrRequest extends FormRequest
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
            'scm_warehouse_id' => 'required|integer|exists:scm_warehouses,id',
            // 'acc_cost_center_id' => 'required|integer|exists:acc_cost_centers,id',
            'department_id' => 'required|integer',
            'date' => 'required|date',
            'remarks' => 'max:255',
            // 'business_unit' => 'required|max:255',
            // 'created_by' => 'required|integer|exists:users,id',
            'scmSrLines.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmSrLines.*.unit' => 'required|max:255|exists:scm_units,name|string',
            'scmSrLines.*.specification' => 'max:255',
            'scmSrLines.*.quantity' => 'required|numeric',
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
