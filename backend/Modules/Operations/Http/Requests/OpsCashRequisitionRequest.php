<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsCashRequisitionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date'                          => ['required', 'date', 'max:50'],
            'requisition_by'                => ['required', 'string', 'max:255'],
            'serial'                        => ['required', 'string', 'max:255'],
            'unit'                          => ['required', 'string', 'max:255'],
            'amount_in_words'               => ['required', 'string'],
            'description'                   => ['nullable', 'string'],
            'salary_unit'                   => ['required', 'string'],
            'pf_no'                         => ['nullable', 'string'],
            'mobile_no'                     => ['required', 'numeric', 'digits_between:10,15'],
            'purpose'                       => ['nullable', 'string'],
            'preferred_adjustment_method'   => ['nullable', 'string', 'max:255'],
            'approximate_adjustment_date'   => ['nullable', 'date', 'max:50'],
            'status'                        => ['nullable', 'string'],
            'business_unit'                 => ['nullable', 'string'],
            'approved_date'                 => ['nullable', 'date', 'max:50'],
            'received_date'                 => ['nullable', 'date', 'max:50'],
            'received_amount'               => ['nullable', 'numeric'],
            'amount'                        => ['required', 'numeric'],
            'amount_bdt'                    => ['nullable', 'numeric'],  
            'amount_usd'                    => ['nullable', 'numeric'],
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
            'requisition_by.required' => 'Requisiton by is required',
            'serial.required' => 'Serial no is required',
            'amount.required' => 'Amount is required',
            'amount_in_words.required' => 'Amount in words is required',
            'salary_unit.required' => 'Salary unit is required',
            'mobile_no.required' => 'Mobile no is required',
            'mobile_no.digits_between' => 'Mobile no must be between :min and :max characters',
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
