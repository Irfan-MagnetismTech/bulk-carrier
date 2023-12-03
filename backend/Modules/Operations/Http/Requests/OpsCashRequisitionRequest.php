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
            'mobile_no'                     => ['required', 'string'],
            'purpose'                       => ['nullable', 'string'],
            'preferred_adjustment_method'   => ['nullable', 'string', 'max:255'],
            'approximate_adjustment_date'   => ['nullable', 'date', 'max:50'],
            'status'                        => ['nullable', 'string'],
            'business_unit'                 => ['nullable', 'string'],
            'approved_date'                 => ['nullable', 'date', 'max:50'],
            'received_date'                 => ['nullable', 'date', 'max:50'],
            'received_amount'               => ['nullable', 'numeric'],
            'amount'                        => ['required', 'numeric'],
            'amount_bdt'                    => ['required', 'numeric'],  
            'amount_usd'                    => ['required', 'numeric'],
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
