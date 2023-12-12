<?php

namespace Modules\Accounts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccAccountOpeningBalanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'acc_account_id' => ['required', 
                            Rule::unique('acc_account_opening_balances')
                            ->where('business_unit', $this->business_unit)
                            ->where('acc_cost_center_id', $this->acc_cost_center_id)
                            ->ignore($this->id),
                        ],
            'dr_amount' => ['required', 'numeric', 'max:9999999999.99'],
            'cr_amount' => ['required', 'numeric', 'max:9999999999.99'],
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
            'acc_account_id.unique'     => 'A record with the combination of cost center and business unit already exists.',
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
