<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsBunkerBillRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $imgData = is_object(request('attachment')) ? request('attachment') : null;
        $imgDataSmr = is_object(request('smr_file_path')) ? request('smr_file_path') : null;
        $mergeData = array_merge($dataArray , ['attachment' => $imgData]);
        $mergeData = array_merge($mergeData , ['smr_file_path' => $imgDataSmr]);
        
        // dd($mergeData);
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
            'scm_vendor_id'     => ['required','exists:scm_vendors,id'],
            'vendor_bill_no'    => ['required', 'string','unique:ops_bunker_bills,vendor_bill_no,'.$this->id],
            'sub_total_bdt'     => ['required', 'numeric'],
            'discount_bdt'      => ['nullable', 'numeric'],
            'grand_total_bdt'   => ['required', 'numeric'],
            'attachment'        => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
            'smr_file_path'     => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
            'remarks'           => ['nullable', 'string', 'max:500'],
            'opsBunkerBillLines.*.ops_bunker_requisition_id' =>  ['nullable', 'numeric','distinct','exists:ops_bunker_requisitions,id'],
            'opsBunkerBillLines.*.rate' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.exchange_rate_bdt' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.exchange_rate_usd' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.amount' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.amount_bdt' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.amount_usd' =>  ['nullable', 'numeric'],
            'opsBunkerBillLines.*.opsBunkerBillLineItems.*.requisition_material' => ['nullable', 'string'],

            // 'opsBunkerBillLines.*.opsBunkerBillLineItems.*.requisition_material' =>  ['nullable', 'string','distinct'],
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
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a date.',
            'scm_vendor_id.required' => 'Vendor is required.',
            'scm_vendor_id.exists' => 'Vendor is not valid.',
            'vendor_bill_no.required' => 'Vendor Bill No. is required.',
            'vendor_bill_no.unique' => 'Vendor Bill No. is already exist.',
            'discount_bdt.numeric' => 'Discount must be numeric.',
            'sub_total_bdt.numeric' => 'Sub total must be numeric.',
            'sub_total_bdt.required' => 'Sub total is required.',
            'grand_total_bdt.numeric' => 'Grand total must be numeric.',
            'grand_total_bdt.required' => 'Grand total is required.',
            'attachment.mimes' => 'Upload file(Supplier Invoice) must be a file allowed types are pdf,doc,docx,jpeg,png,gif,xlsx.',
            'attachment.max' => 'Upload file(Supplier Invoice) should not exceed 2048 kilobytes (2 MB).',
            'smr_file_path.mimes' => 'Upload file(SRM Copy) must be a file allowed types are pdf,doc,docx,jpeg,png,gif,xlsx.',
            'smr_file_path.max' => 'Upload file(SRM Copy) should not exceed 2048 kilobytes (2 MB).',
            'remarks.max' => 'Remarks may not be greater than :max characters.',
            'opsBunkerBillLines.*.ops_bunker_requisition_id.distinct' => 'PR No. can not be same for row :position.',
            'opsBunkerBillLines.*.ops_bunker_requisition_id.exists' => 'PR No. is not valid for row :position.',
            
            'opsBunkerBillLines.*.rate.numeric' => 'Rate must be numeric for row is :position.',
            'opsBunkerBillLines.*.exchange_rate_bdt.numeric' => 'Exchange Rate(BDT) must be numeric for row is :position.',
            'opsBunkerBillLines.*.exchange_rate_usd.numeric' => 'Exchange Rate(USD) must be numeric for row is :position.',
            'opsBunkerBillLines.*.amount.numeric' => 'Amount must be numeric for row is :position.',
            'opsBunkerBillLines.*.amount_bdt.numeric' => 'Amount(BDT) must be numeric for row is :position.',
            'opsBunkerBillLines.*.amount_usd.numeric' => 'Amount(USD) must be numeric for row is :position.',

            // 'opsBunkerBillLines.*.opsBunkerBillLineItems.*.requisition_material.distinct' => 'Bunker can not be same for row :position.',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->customValidation($validator);
        });
    }

    protected function customValidation($validator)
    {
        $requisitionMaterials = $this->input('opsBunkerBillLines');

        $errors=[];
        foreach($requisitionMaterials as $pr => $requisitionMat){
            $items= [];
            foreach($requisitionMat['opsBunkerBillLineItems'] as $key => $item){

                $items[]=$item['requisition_material'];

            }
            if (count($items) !== count(array_unique($items))) {
                // $errors[]='Bunker can not be same in individual PR for row '.($pr+1).'.';
                $validator->errors()->add('opsBunkerBillLines.*.opsBunkerBillLineItems.*.requisition_material', 'Bunker can not be same in individual PR for row '.($pr+1).'.');
            }
        }
    }
}
