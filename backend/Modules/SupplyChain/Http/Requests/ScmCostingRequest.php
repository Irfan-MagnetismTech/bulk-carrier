<?php

namespace Modules\SupplyChain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScmCostingRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $data =  request('data');
        $dataArray = json_decode($data, true);
        // $imgData = is_object(request('attachment')) ? request('attachment') : null;
        // $mergeData = array_merge($dataArray, ['attachment' => $imgData, 'excel' => request('excel')]);

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
            //
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
