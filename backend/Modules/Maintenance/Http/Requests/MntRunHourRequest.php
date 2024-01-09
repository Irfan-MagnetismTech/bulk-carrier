<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MntRunHourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $method = $this->method();

        if($method == "PUT"){
            if ($this->running_hour == 0 && $this->previous_run_hour == 0) {
                return [
                    'present_run_hour' => 'required|integer|min:1',
                    'updated_on' => 'required|date'
                ];
            }
        }

        return [
            'running_hour' => 'required|integer|min:1',
            'updated_on' => 'required|date'
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
    

    public function attributes()
    {
        return[
            'present_run_hour' => 'present running hour'
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
