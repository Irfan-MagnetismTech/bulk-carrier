<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageBudgetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {     
        return [
            'ops_vessel_id'         => ['required', 'numeric'],
            'ops_voyage_id'         => ['required', 'numeric'],
            'ops_expense_head_id'   => ['nullable', 'numeric'],
            'title'                 => ['required', 'string'],
            'currency'              => ['required'],
            'effective_from'              => ['required'],
            'effective_till'              => ['required'],
            

            // 'total'                 => ['required', 'numeric'],
            'opsVoyageBudgetEntries.*.amount'   => ['required', 'numeric'],
            'opsVoyageBudgetEntries.*.amount_bdt'   => ['nullable', 'numeric'],
            'opsVoyageBudgetEntries.*.amount_usd'   => ['nullable', 'numeric'],
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
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_voyage_id.required' => 'Voyage is required',
            // 'ops_expense_head_id.required' => 'Expense head is required',
            'title.required' => 'Title is required',
            'total.required' => 'Total is required',
            'opsVoyageBudgetEntries.*.perticular.required' => 'Particular is required for row is :position.',
            'opsVoyageBudgetEntries.*.currency.required' => 'Currency is required for row is :position.',
            'opsVoyageBudgetEntries.*.percentage.required' => 'Percentage is required for row is :position.',
            'opsVoyageBudgetEntries.*.amount.required' => 'Amount is required for row is :position.',
            'opsVoyageBudgetEntries.*.percentage.numeric' => 'Percentage must be numeric for row is :position.',
            'opsVoyageBudgetEntries.*.amount.numeric' => 'Amount must be numeric for row is :position.',
            'opsVoyageBudgetEntries.*.exchange_rate_bdt.numeric' => 'Exchange rate (BDT) must be numeric for row is :position.',
            'opsVoyageBudgetEntries.*.exchange_rate_usd.numeric' => 'Exchange rate (USD) must be numeric for row is :position.',
            'opsVoyageBudgetEntries.*.amount_bdt.numeric' => 'Amount (BDT) must be numeric for row is :position.',
            'opsVoyageBudgetEntries.*.amount_usd.numeric' => 'Amount (USD) must be numeric for row is :position.',
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
