<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmOpeningStockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
                'scmOpeningStockLines.*.quantity' => ['required', 'numeric', 'min:1'],
                //minimum amount validation
        
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
            'scmOpeningStockLines.*.quantity.min' => 'In row :position you have given :input but minimum amount is :min'
            
        ];
    }
     

    // [:attribute] [:index] [:rule] [:size] [:values] [:custom] [:extra] [:attribute] [:rule] [:parameters] [:size] [:values] [:custom] [:extra] [:value] [:max] [:min]',
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
