<?php

namespace Modules\Crew\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrwIncidentRequest extends FormRequest
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
            'ops_vessel_id'                           => ['required', 'integer', 'exists:ops_vessels,id'],            
            'date_time'                               => ['required', 'date',
                                                            Rule::unique('crw_incidents')
                                                                ->where('ops_vessel_id', $this->ops_vessel_id)
                                                                ->where('business_unit', $this->business_unit)
                                                                ->ignore($this->id),
                                                        ],
            'type'                                    => 'required|string|max:255',
            'location'                                => 'required|string|max:255',
            'reported_by'                             => 'required|string|max:255',
            'attachment'                              => 'nullable|mimes:pdf,doc,docx,jpeg,png,gif,xlsx|max:2048',
            'description'                             => 'nullable|string|max:500',
            'business_unit'                           => 'required|in:PSML,TSLL',
            'crwIncidentParticipants.*.crw_crew_id'   => ['required', 'distinct', 'exists:crw_crew_profiles,id'],
            'crwIncidentParticipants.*.injury_status' => 'required|string|max:255',
            'crwIncidentParticipants.*.notes'         => 'string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'ops_vessel_id.exists'                          => 'The Vessel Name does not exists.',            
            'date_time.unique'                              => 'A record with the combination of vessel name, incident date & time already exists.',
            'crwIncidentParticipants.*.crw_crew_id.exists'  => 'The Crew Name does not exists[:position].',
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
