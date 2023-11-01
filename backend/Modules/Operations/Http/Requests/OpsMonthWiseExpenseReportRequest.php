<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsMonthWiseExpenseReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id'     => ['required', 'numeric', 'max:20'],
            'from_date'         => ['required', 'string'],
            'till_date'         => ['required', 'string'],
            'grand_total_cost'  => ['required', 'numeric'],
            'left_over_amount'  => ['required', 'numeric'],
            'remarks'           => ['required', 'string'],
            'business_unit'     => ['required', 'string', 'max:255'],
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
