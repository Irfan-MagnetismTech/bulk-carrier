<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Validation\Rule;
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
            'scmWcsServices.*.scm_wr_id' => ['required','exists:scm_wrs,id','integer', Rule::unique('scm_wcs_services')->ignore($this->route('work_c'), 'id'),],

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
            'effective_date.required' => 'Effective Date is required.',
            'effective_date.date' => 'Effective Date must be a date.',
            'effective_date.before_or_equal' => 'Effective Date must be before or equal to Expire Date.',

            'scmWcsServices.*.scm_wr_id.unique' => 'In row no :position Work Requisition is already taken.',
            'scmWcsServices.*.scm_wr_id.required' => 'In row no :position Work Requisition is required.',
            'scmWcsServices.*.scm_wr_id.integer' => 'In row no :position Work Requisition must be an integer.',
            'scmWcsServices.*.scm_wr_id.exists' => 'In row no :position Work Requisition is not found.',
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
