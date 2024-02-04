<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WcsSupplierSelectionRequest extends FormRequest
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
            'auditor_remarks' => 'nullable|max:250',
            'auditor_remarks_date' => 'required|date',
            'selection_ground' => 'required|max:250',
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
            'auditor_remarks.max' => 'Auditor Remarks must be less than 300 characters',
            'auditor_remarks_date.required' => 'Auditor Remarks Date is required.',
            'auditor_remarks_date.date' => 'Auditor Remarks Date must be a date.',
            'selection_ground.max' => 'Auditor Remarks must be less than 300 characters',
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
