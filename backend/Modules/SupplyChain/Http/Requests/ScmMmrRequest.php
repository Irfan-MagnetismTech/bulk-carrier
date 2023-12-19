<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmMmrRequest extends FormRequest
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
            'date' => 'required|date',
            'delivery_date' => 'required|date',
            'from_warehouse_id' => 'required|exists:scm_warehouses,id|integer|gt:0',
            'to_warehouse_id' => 'required|exists:scm_warehouses,id|integer|gt:0',
            'requested_by' => 'required',
            'requested_for' => 'required',
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
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',
            'delivery_date.required' => 'Delivery Date is required',
            'delivery_date.date' => 'Delivery Date must be a valid date',
            'from_warehouse_id.required' => 'From Warehouse is required',
            'from_warehouse_id.integer' => 'From Warehouse must be an integer',
            'from_warehouse_id.gt' => 'From Warehouse must be greater than 0',
            'from_warehouse_id.exists' => 'From Warehouse does not exist',
            'to_warehouse_id.required' => 'To Warehouse is required',
            'to_warehouse_id.integer' => 'To Warehouse must be an integer',
            'to_warehouse_id.gt' => 'To Warehouse must be greater than 0',
            'to_warehouse_id.exists' => 'To Warehouse does not exist',
            'requested_by.required' => 'Requested By is required',
            'requested_for.required' => 'Requested For is required',

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
