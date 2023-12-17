<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccAdvanceAdjustmentRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $data        = request('data');
        $dataArray   = json_decode($data, true);
        // $attachments = is_array(request('attachments')) ? request('attachments') : null;
        $mergeData   = array_merge($dataArray, ['attachment' => request('attachments')]);
        // dd($dataArray, $mergeData);


        $this->replace($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            // 'acc_cost_center_id'                     => 'required',
            // 'acc_cash_requisition_id'                => ['required', Rule::unique('acc_advance_adjustments')->ignore($this->id)],
            // 'adjustment_date'                        => 'required|date',
            // 'adjustment_amount'                      => 'required|numeric',
            // 'business_unit'                          => 'required|in:PSML,TSLL',
            // 'accAdvanceAdjustmentLines.*.particular' => 'required|string|max:255',
            // 'accAdvanceAdjustmentLines.*.amount'     => 'required|numeric|between:0,9999999.99',
            // 'accAdvanceAdjustmentLines.*.attachment' => 'nullable|string|max:255',
            // 'accAdvanceAdjustmentLines.*.remarks'    => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            // 'acc_cost_center_id.unique'                     => 'required',
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
