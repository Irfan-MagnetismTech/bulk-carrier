<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmMrrRequest extends FormRequest
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
            // 'type' => 'required|integer',
            'date' => 'required|date',
            'scm_po_id' => 'sometimes|nullable|integer|exists:scm_pos,id',
            'scm_pr_id' => 'sometimes|nullable|integer|exists:scm_prs,id',
            'scm_warehouse_id' => 'required|integer|exists:scm_warehouses,id',
            'scm_lc_record_id' => 'sometimes|nullable|integer|exists:scm_lc_records,id',
            'scm_cs_id' => 'sometimes|nullable||integer|exists:scm_cs,id',
            // 'acc_cost_center_id' => 'required|integer|exists:acc_cost_centers,id',
            'remarks' => 'max:255',
            'challan_no' => 'max:255',
            // 'is_qc_passed' => 'required|integer',
            'qc_remarks' => 'max:255|required',
            // 'business_unit' => 'required|max:255',
            // 'created_by' => 'required|integer|exists:users,id',
            'is_completed' => 'required|integer',
            'purchase_center' => 'required|max:255',
            'scmMrrLines.*.scm_material_id' => 'required|exists:scm_materials,id|integer',
            'scmMrrLines.*.unit' => 'required|max:255|exists:scm_units,name|string',
            'scmMrrLines.*.brand' => 'max:255',
            'scmMrrLines.*.model' => 'max:255',
            'scmMrrLines.*.quantity' => 'required|numeric',
            'scmMrrLines.*.rate' => 'required|numeric'
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
