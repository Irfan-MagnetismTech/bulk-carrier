<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrwAgencyContractRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data      = request('data');
        $dataArray = json_decode($data, true);

        $mergeData = array_merge($dataArray, ['attachment' => request('attachment')]);

        $this->replace($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_agency_id'        => 'required|integer',
            'billing_cycle'        => 'required|integer|max:365',
            'billing_currency'     => 'required|string|max:255',
            'validity_from'        => 'required|date',
            'validity_till'        => 'required|date|after_or_equal:validity_from',
            'service_offered'      => 'required|string|max:700',
            'terms_and_conditions' => 'required|string|max:1000',
            'remarks'              => 'nullable|string|max:700',
            'attachment'           => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048', // accepting documents, images, and Excel files
            'account_holder_name'  => 'required|string|max:255',
            'bank_name'            => 'required|string|max:255',
            'bank_address'         => 'required|string|max:255',
            'account_no'           => 'required|string|max:255',
            'swift_code'           => 'nullable|string|max:255',
            'business_unit'        => 'required|in:PSML,TSLL',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
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
