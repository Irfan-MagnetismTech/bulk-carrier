<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccCashRequisitionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'acc_cost_center_id'                   => 'required|integer',
            'applied_date'                         => 'required|date',
            // 'mpr_id'                               => 'nullable|integer',
            'total_amount'                         => 'required|numeric',
            'purpose'                              => 'required|string|max:700',
            'business_unit'                        => 'required|in:PSML,TSLL',
            'accCashRequisitionLines.*.particular' => 'required|string',
            'accCashRequisitionLines.*.amount'     => 'required|numeric',
            'accCashRequisitionLines.*.remarks'    => 'max:700',
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
