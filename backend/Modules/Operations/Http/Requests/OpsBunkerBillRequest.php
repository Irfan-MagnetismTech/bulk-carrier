<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsBunkerBillRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
        $mergeData = array_merge($mergeData , ['smr_file_path' => request('smr_file_path')]);
        $this->replace($mergeData);
    }
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
            'sub_total'         => ['required', 'numeric'],
            'discount'          => ['nullable', 'numeric'],
            'grand_total'       => ['required', 'numeric'],
            // 'attachment'        => ['nullable', 'string'],
            // 'smr_file_path'     => ['nullable', 'string'],
            'remarks'           => ['nullable', 'string', 'max:2550'],
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
