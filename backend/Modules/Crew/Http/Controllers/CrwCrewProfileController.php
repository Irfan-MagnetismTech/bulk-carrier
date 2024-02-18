<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewProfile;
use Modules\Crew\Http\Requests\CrwCrewProfileRequest;

class CrwCrewProfileController extends Controller
{
    /**
     * @param FileUploadService $fileUpload
     */
    public function __construct(private FileUploadService $fileUpload)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrewProfiles = CrwCrewProfile::with('educations', 'trainings', 'experiences', 'languages', 'references', 'nominees', 'crewRank')
                ->globalSearch($request->all());

            return response()->success('Retrieved Succesfully', $crwCrewProfiles, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrwCrewProfileRequest $request)
    {
        // dd($request->all());

        try {
            DB::transaction(function () use ($request)
            {
                $crwCrewProfileData = $request->only('crw_recruitment_approval_id', 'hired_by', 'agency_id', 'department_name', 'crw_rank_id', 'employee_type', 'is_officer', 'first_name', 'last_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'picture', 'attachment', 'business_unit');
                // $crwCrewProfileData = json_decode($request->get('data'),true);
                $crwCrewProfileData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-profile');
                $crwCrewProfileData['picture']    = $this->fileUpload->handleFile($request->picture, 'crw/crew-profile');
                $crwCrewProfileData['full_name']  = $request->first_name . ' ' . $request->last_name;

                $crwCrewProfile = CrwCrewProfile::create($crwCrewProfileData);
                $crwCrewProfile->educations()->createMany($request->educations);
                $crwCrewProfile->trainings()->createMany($request->trainings);
                $crwCrewProfile->experiences()->createMany($request->experiences);
                $crwCrewProfile->languages()->createMany($request->languages);
                $crwCrewProfile->references()->createMany($request->references);
                $crwCrewProfile->nominees()->createMany($request->nominees);

            });
            return response()->success('Created Successfully', '', 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrewProfile  $crwCrewProfile
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewProfile $crwCrewProfile)
    {
        try {
            return response()->success('Retrieved successfully', $crwCrewProfile->load('crewRank','crewRecruitmentApproval','crewAgency','educations', 'trainings', 'experiences', 'languages', 'references', 'nominees',  'crewRank', 'crewRecruitmentApproval', 'crewAgency'), 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CrwCrewProfile  $crwCrewProfile
     * @return \Illuminate\Http\Response
     */
    public function update(CrwCrewProfileRequest $request, CrwCrewProfile $crwCrewProfile)
    {
        // dd($crwCrewProfile);
        try {
            DB::transaction(function () use ($request, $crwCrewProfile)
            {
                $crwCrewProfileData = $request->only('crw_recruitment_approval_id', 'hired_by', 'agency_id', 'department_name', 'crw_rank_id', 'employee_type', 'is_officer', 'first_name', 'last_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'picture', 'attachment', 'business_unit');
                // $crwCrewProfileData = json_decode($request->get('data'),true);
                $crwCrewProfileData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-profile', $crwCrewProfile->attachment);
                $crwCrewProfileData['picture']    = $this->fileUpload->handleFile($request->picture, 'crw/crew-profile', $crwCrewProfile->picture);
                $crwCrewProfileData['full_name']  = $request->first_name . ' ' . $request->last_name;

                $crwCrewProfile->update($crwCrewProfileData);

                $crwCrewProfile->educations()->delete();
                $crwCrewProfile->trainings()->delete();
                $crwCrewProfile->experiences()->delete();
                $crwCrewProfile->languages()->delete();
                $crwCrewProfile->references()->delete();
                $crwCrewProfile->nominees()->delete();

                $crwCrewProfile->educations()->createMany($request->educations);
                $crwCrewProfile->trainings()->createMany($request->trainings);
                $crwCrewProfile->experiences()->createMany($request->experiences);
                $crwCrewProfile->languages()->createMany($request->languages);
                $crwCrewProfile->references()->createMany($request->references);
                $crwCrewProfile->nominees()->createMany($request->nominees);

            });

            return response()->success('Updated successfully', $crwCrewProfile, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrewProfile  $crwCrewProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewProfile $crwCrewProfile)
    {
        try {
            $crwCrewProfile->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
