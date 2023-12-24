<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageExpenditureRequest extends FormRequest
{

    protected function prepareForValidation(){
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $mergeData = array_merge($dataArray , ['attachment' => request('attachment')]);
        // dd($mergeData['ops_voyage_id']);
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
            'ops_voyage_id'         => ['required'],
            'ops_vessel_id'         => ['required'],
            'port_code'             => ['required', 'string', 'max:255'],
            'currency'              => ['required', 'string', 'max:255'],
            'sub_total_usd'         => ['required', 'numeric'],
            'sub_total_bdt'         => ['required', 'numeric'],
            'grand_total_usd'       => ['required', 'numeric'],
            'grand_total_bdt'       => ['required', 'numeric'],
            'expense_json'          => ['nullable', 'string'],
            'date'                  => ['nullable', 'string', 'max:255'],
            'business_unit'         => ['required', 'string', 'max:255'],
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
