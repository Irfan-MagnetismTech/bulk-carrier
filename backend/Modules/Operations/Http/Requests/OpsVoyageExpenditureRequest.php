<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageExpenditureRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $imgData = is_object(request('attachment')) ? request('attachment') : null;
        $mergeData = array_merge($dataArray , ['attachment' => $imgData]);
        // dd($mergeData['ops_voyage_id']);
        $this->replace($mergeData);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_voyage_id'         => ['required','exists:ops_voyages,id'],
            'ops_vessel_id'         => ['required','exists:ops_vessels,id'],
            'port_code'             => ['required', 'string', 'max:255'],
            'currency'              => ['required', 'string', 'max:255'],
            'sub_total_usd'         => ['required', 'numeric'],
            'sub_total_bdt'         => ['required', 'numeric'],
            'grand_total_usd'       => ['required', 'numeric'],
            'grand_total_bdt'       => ['required', 'numeric'],
            'expense_json'          => ['nullable', 'string'],
            'date'                  => ['nullable', 'date'],
            'business_unit'         => ['required', 'string', 'max:255'],
            'attachment'        =>'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048', 

            'opsVoyageExpenditureEntries.*.ops_voyage_id'   => ['required', 'exists:ops_voyages,id'],
            'opsVoyageExpenditureEntries.*.ops_voyage_expenditure_id'   => ['required', 'exists:ops_voyage_expenditures,id'],
            'opsVoyageExpenditureEntries.*.ops_expense_head_id'   => ['nullable', 'exists:ops_expense_heads,id'],
            'opsVoyageExpenditureEntries.*.invoice_date'   => ['required', 'date'],
            'opsVoyageExpenditureEntries.*.invoice_no'   => ['required', 'string'],
            'opsVoyageExpenditureEntries.*.currency'   => ['required', 'numeric'],
            'opsVoyageExpenditureEntries.*.rate'   => ['required', 'numeric'],

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
            'ops_vessel_id.required' => 'Vessel is required',
            'ops_vessel_id.exists'   => 'Vessel is not valid',
            'ops_voyage_id.required' => 'Voyage is required',
            'ops_voyage_id.exists'   => 'Voyage is not valid',
            'port_code.required'   => 'Port Code is required',
            'currency.required'   => 'Currency is required',
            'sub_total_usd.required'   => 'Sub total (USD) is required',
            'sub_total_usd.numeric'   => 'Sub total (USD) must be numeric',
            'sub_total_bdt.required'   => 'Sub total (BDT) is required',
            'sub_total_bdt.numeric'   => 'Sub total (bdt) must be numeric',
            'grand_total_usd.required'   => 'Grand total (USD) is required',
            'grand_total_usd.numeric'   => 'Grand total (USD) must be numeric',
            'grand_total_bdt.required'   => 'Grand total (BDT) is required',
            'grand_total_bdt.numeric'   => 'Grand total (BDT) must be numeric',
            'date.date' => 'Date must be a date.',
            'attachment.mimes' => 'Attachment must be an excel file of type: pdf,doc,docx,jpeg,png,gif,xlsx.',
            'attachment.max' => 'Attachment should not exceed 2048 kilobytes (2 MB).',


            'opsVoyageExpenditureEntries.*.ops_voyage_id.required' => 'Voyage is required for row is :position.',
            'opsVoyageExpenditureEntries.*.ops_voyage_id.exists'   => 'Voyage is not valid for row is :position.',
            'opsVoyageExpenditureEntries.*.ops_voyage_expenditure_id.required' => 'Voyage expenditure is required for row is :position.',
            'opsVoyageExpenditureEntries.*.ops_voyage_expenditure_id.exists'   => 'Voyage expenditure is not valid for row is :position.',
            'opsVoyageExpenditureEntries.*.ops_expense_head_id.required' => 'Expense head is required for row is :position.',
            'opsVoyageExpenditureEntries.*.ops_expense_head_id.exists'   => 'Expense head is not valid for row is :position.',
            'opsVoyageExpenditureEntries.*.invoice_date.required' => 'Invoice date is required for row is :position.',
            'opsVoyageExpenditureEntries.*.invoice_date.date'   => 'Invoice date must be a date for row is :position.',
            'opsVoyageExpenditureEntries.*.currency.required' => 'Currency is required for row is :position.',
            'opsVoyageExpenditureEntries.*.rate.required' => 'Rate is required for row is :position.',
            
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
