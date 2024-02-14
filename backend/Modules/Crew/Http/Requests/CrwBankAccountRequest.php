<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwBankAccountRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data       = request('data');
        $dataArray  = json_decode($data, true);
        $attachment = is_object(request('attachment')) ? request('attachment') : null;
        $mergeData  = array_merge($dataArray, ['attachment' => $attachment]);

        $this->replace($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'crw_crew_id'      => ['required', 'integer', 'exists:crw_crew_profiles,id', 
                                    $this->is_active == 1 ? Rule::unique('crw_bank_accounts')->where('is_active', true)->ignore($this->id) : null],
            'bank_name'        => 'required|string|max:255',
            'branch_name'      => 'required|string|max:255',
            'routing_number'   => 'string|max:255',
            'account_name'     => 'required|string|max:255',
            'account_number'   => 'required|string|max:255',
            'benificiary_name' => 'required|string|max:255',
            'attachment'       => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
            'business_unit'    => 'required|in:PSML,TSLL',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'crw_crew_id.unique' => 'The Crew already has an active account. Please deactivate that account first.',
            'crw_crew_id.exists'   => 'The Crew Name does not exists[:position].',            
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
