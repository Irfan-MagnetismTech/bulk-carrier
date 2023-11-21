<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsBunkerBillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'date'              => ['required', 'date'],
            'scm_vendor_id'     => ['required', 'numeric', 'max:20'],
            'vendor_bill_no'    => ['required', 'string', 'max:255'],
            'remarks'           => ['required', 'string', 'max:2550'],
            'attachment'        => ['required', 'string'],
            'smr_file_path'     => ['required', 'string'],
            'sub_total'         => ['required', 'numeric', 'max:255'],
            'discount'          => ['required', 'numeric', 'max:255'],
            'grand_total'       => ['required', 'numeric', 'max:255'],
            'business_unit'     => ['required', 'string'],
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
