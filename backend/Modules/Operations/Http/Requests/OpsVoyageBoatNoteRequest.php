<?php

namespace Modules\Operations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpsVoyageBoatNoteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        // dd(request('info'));
        $data=  request('info');
        $dataArray = json_decode($data, true);
        $mergeData = array_merge($dataArray , ['attachment' => request('attachments')]);
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
            'ops_voyage_id'      => ['required'],
            'ops_vessel_id'      => ['required'],
            'type'               => ['required'],
            'vessel_draft'       => ['nullable', 'string'],
            'water_density'      => ['nullable', 'string'],
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
