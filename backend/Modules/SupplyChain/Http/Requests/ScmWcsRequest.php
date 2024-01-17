<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmWcsRequest extends FormRequest
{
        //preparefor validation
        protected function prepareForValidation(): void
        {
            $data =  request('data');
            $dataArray = json_decode($data, true);            
    
            // $mergeData = array_merge($dataArray, ['attachment' => request('attachment'), 'excel' => request('excel')]);
    
            $this->replace($dataArray);
        }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'effective_date' => 'required|date|before_or_equal:expire_date',
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
            'effective_date.required' => 'Effective Date is required',
            'effective_date.date' => 'Effective Date must be a date',
            'effective_date.before_or_equal' => 'Effective Date must be before or equal to Expire Date',
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
