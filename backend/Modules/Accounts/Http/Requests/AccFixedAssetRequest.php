<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccFixedAssetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'acc_cost_center_id'           => 'required|integer',
            'scm_mrr_id'                   => 'required|integer',
            'scm_material_id'              => 'required|integer',
            'brand'                        => 'max:255',
            'model'                        => 'max:255',
            'serial'                       => 'max:255',
            'acc_parent_account_id'        => 'required|integer',
            'acc_account_id'               => 'required|integer',
            'asset_tag'                    => ['required', 'string', 'max:255',
                                                Rule::unique('acc_fixed_assets')
                                                ->where('business_unit', $this->business_unit)
                                                ->ignore($this->id),
                                            ],
            'location'                     => 'nullable|string|max:255',
            'acquisition_date'             => 'required|date',
            'useful_life'                  => 'required|numeric|min:1',
            'depreciation_rate'            => 'required|numeric|min:0|max:100',
            'acquisition_cost'             => 'required|numeric|min:0',
            'business_unit'                => 'required|in:PSML,TSLL',
            'fixedAssetCosts'              => 'required|array|min:1',
            'fixedAssetCosts.*.particular' => 'required|string|max:255',
            'fixedAssetCosts.*.remarks'    => 'nullable|string|max:255',
            'fixedAssetCosts.*.amount'     => 'required|numeric|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'asset_tag.unique'         => 'The asset tag field is exists within this business unit.',
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
