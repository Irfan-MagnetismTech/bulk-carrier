<?php

namespace Modules\Maintenance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class MntJobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ops_vessel_id' => 'required',
            'mnt_item_id' => ['required', Rule::unique('mnt_jobs')->where('ops_vessel_id', $this->ops_vessel_id)->ignore($this->id)],
            'mntJobLines.*.job_description' => 'required|max:100',
            'mntJobLines.*.cycle' => 'required|integer|min:1',
            'mntJobLines.*.cycle_unit' => 'required|in:Hours,Days,Weeks,Months',
            'mntJobLines.*.min_limit' => 'required|integer|lt:mntJobLines.*.cycle',
            'mntJobLines.*.last_done' => 'required|date',
        ];
    }

    public function withValidator($validator)
    {
        $messages= $validator->errors()->messages();
        if ($messages) {
            foreach($messages as $field =>$messageArray){
                $table= Str::before($field, '.');
                $index= Str::after($field, $table.'.');
                $index= Str::before($index, '.');
                foreach($messageArray as $key => $message){
                    $messages[$field][$key]= Str::before($message, 'key'.$index) .' '. ++$index;
                }        
            }
            $response= new \Illuminate\Http\JsonResponse([
                'message' =>'The given data was invalid',
                'errors' => $messages
            ], 422);
    
            throw new HttpResponseException($response);
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'mnt_item_id.unique' => 'Jobs for selected items on selected vessel already exists.',
            'mntJobLines.*.job_description.required' => 'Job description is a required field for line no. key:index.',
            'mntJobLines.*.job_description.max' => 'Job description must not exceed 255 characters for line no. key:index.',
            'mntJobLines.*.cycle.required' => 'Please enter Cycle number for line no. key:index.',
            'mntJobLines.*.cycle.min' => 'Cycle number should be minimum 1 for line no. key:index.',
            'mntJobLines.*.cycle.integer' => 'Cycle should be a number for line no. key:index.',
            'mntJobLines.*.cycle_unit.required' => 'Please select Cycle Unit for line no. key:index.',
            'mntJobLines.*.cycle_unit.in' => 'Cycle unit value is invalid for line no. key:index.',
            'mntJobLines.*.min_limit.lt' => 'Add to upcoming list value should be less than Cycle value for line no. key:index.',
            'mntJobLines.*.min_limit.required' => 'Add to upcoming list is a required field for line no. key:index.',
            'mntJobLines.*.min_limit.integer' => 'Add to upcoming list should be a number for line no. key:index.',
            'mntJobLines.*.last_done.required' => 'Last done is a required field for line no. key:index.',
            'mntJobLines.*.last_done.date' => 'Last done should be a valid date for line no. key:index.',

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
