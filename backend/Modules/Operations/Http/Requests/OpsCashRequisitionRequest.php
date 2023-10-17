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
            'description'                   => ['required', 'string'],
            'salary_unit'                   => ['required', 'string'],
            'pf_no'                         => ['required', 'string'],
            'mobile_no'                     => ['required', 'string', 'max:255'],
            'purpose'                       => ['required', 'string'],
            'preferred_adjustment_method'   => ['required', 'string', 'max:255'],
            'approximate_adjustment_date'   => ['required', 'date', 'max:50'],
            'status'                        => ['required', 'string'],
            'business_unit'                 => ['required', 'string'],
            'approved_date'                 => ['required', 'date', 'max:50'],
            'received_date'                 => ['required', 'date', 'max:50'],
            'received_amount'               => ['required', 'numeric'],
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
