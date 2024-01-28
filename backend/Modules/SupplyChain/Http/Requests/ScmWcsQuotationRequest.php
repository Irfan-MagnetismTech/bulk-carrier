<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmWcsQuotationRequest extends FormRequest
{

    //preparefor validation
    protected function prepareForValidation(): void
    {
        $data =  request('data');
        $dataArray = json_decode($data, true);
        $attachment = is_object(request('attachment')) ? request('attachment') : null;
        $mergeData = array_merge($dataArray, ['attachment' => $attachment]);

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
            //
            'attachment' => 'nullable|mimes:xlsx,pdf,jpg,png,jpeg,doc,docx',
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
            'attachment.required' => 'Attachment is required',
            'attachment.mimes' => 'Attachment must be an xlsx,pdf,jpg,png,jpeg,doc,docx',

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