<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccAdvanceAdjustmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'acc_cost_center_id'                     => 'required',
            'acc_cash_requisition_id'                => 'required|unique',
            'adjustment_date'                        => 'required|date',
            'adjustment_amount'                      => 'required|numeric',
            'business_unit'                          => 'required|in:PSML,TSLL',
            'accAdvanceAdjustmentLines.*.particular' => 'required|string|max:255',
            'accAdvanceAdjustmentLines.*.amount'     => 'required|numeric|between:0,9999999.99',
            'accAdvanceAdjustmentLines.*.attachment' => 'nullable|string|max:255',
            'accAdvanceAdjustmentLines.*.remarks'    => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
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
