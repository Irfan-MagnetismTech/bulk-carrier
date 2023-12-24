<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccDepreciationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'month_year'                                => ['required', ],
            'applied_date'                              => 'required|date',
            'acc_cost_center_id'                        => ['required',
                                                            Rule::unique('acc_depreciations')
                                                            ->where('month_year', $this->month_year)
                                                            ->where('business_unit', $this->business_unit)
                                                            ->ignore($this->id),
                                                        ],
            'business_unit'                             => 'required|in:PSML,TSLL',
            'accDepreciationLines.*.acc_fixed_asset_id' => 'required',
            'accDepreciationLines.*.depreciation_rate'  => 'required|numeric|min:0|max:100',
            'accDepreciationLines.*.amount'             => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'acc_cost_center_id.unique'         => 'A record with the combination of Year and business unit already exists.',
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
