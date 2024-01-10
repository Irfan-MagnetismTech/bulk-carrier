<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmAdjustmentRequest extends FormRequest
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
            'date' => 'required|date',
            'scm_warehouse_id' => 'required|exists:scm_warehouses,id',
            'acc_cost_center_id' => 'nullable|exists:acc_cost_centers,id',
            'type' => 'required',
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

            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',

            'scm_warehouse_id.required' => 'Warehouse is required',
            'scm_warehouse_id.exists' => 'Warehouse is not valid',

            'acc_cost_center_id.exists' => 'Cost center is not valid',

            'type.required' => 'Type is required',
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
