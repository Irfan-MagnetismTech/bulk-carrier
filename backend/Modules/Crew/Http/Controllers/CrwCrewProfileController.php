<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewProfile;
use App\Services\FileUploadService;

class CrwCrewProfileController extends Controller
{
    public function __construct(private FileUploadService $fileUpload)
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $profiles = CrwCrewProfile::with('educations')->get();

            $crwCrewProfiles = CrwCrewProfile::with('educations', 'trainings', 'experiences', 'languages', 'references', 'nominees', 'crewBasicInfo', 'crewRank')->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);


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
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwCrewProfileData = $request->only('crw_recruitment_approval_id', 'hired_by', 'agency_id', 'rank_id', 'department_id', 'first_name', 'last_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'business_unit');
                $crwCrewProfileData = json_decode($request->get('data'),true);
                $crwCrewProfileData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-profile');
                $crwCrewProfileData['picture'] = $this->fileUpload->handleFile($request->picture, 'crw/crew-profile');

                $crwCrewProfile = CrwCrewProfile::create($crwCrewProfileData);
                $crwCrewProfile->educations()->createMany($crwCrewProfileData['educations']);
                $crwCrewProfile->trainings()->createMany($crwCrewProfileData['trainings']);
                $crwCrewProfile->experiences()->createMany($crwCrewProfileData['experiences']);
                $crwCrewProfile->languages()->createMany($crwCrewProfileData['languages']);
                $crwCrewProfile->references()->createMany($crwCrewProfileData['references']);
                $crwCrewProfile->nominees()->createMany($crwCrewProfileData['nominees']);

                return response()->success('Created Successfully', $crwCrewProfile, 201);
            });
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
            return response()->success('Retrieved successfully', $crwCrewProfile->load('educations', 'trainings', 'experiences', 'languages', 'references', 'nominees', 'crewBasicInfo', 'crewRank', 'crewRecruitmentApproval', 'crewAgency'), 200);
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
    public function update(Request $request, CrwCrewProfile $crwCrewProfile)
    {
        try {
            DB::transaction(function () use ($request, $crwCrewProfile)
            {
                $crwCrewProfileData = $request->only('crw_recruitment_approval_id', 'hired_by', 'agency_id','rank_id', 'department_id', 'crw_rank_id', 'first_name', 'last_name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'religion', 'marital_status', 'nationality', 'nid_no', 'passport_no', 'passport_issue_date', 'blood_group', 'height', 'weight', 'pre_address', 'pre_city', 'pre_mobile_no', 'pre_email', 'per_address', 'per_city', 'per_mobile_no', 'per_email', 'business_unit');
                $crwCrewProfileData = json_decode($request->get('data'),true);
                $crwCrewProfileData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-profile', $crwCrewProfile->attachment);
                $crwCrewProfileData['picture'] = $this->fileUpload->handleFile($request->picture, 'crw/crew-profile', $crwCrewProfile->picture);

                $crwCrewProfile->update($crwCrewProfileData);

                $crwCrewProfile->educations()->delete();
                $crwCrewProfile->trainings()->delete();
                $crwCrewProfile->experiences()->delete();
                $crwCrewProfile->languages()->delete();
                $crwCrewProfile->references()->delete();
                $crwCrewProfile->nominees()->delete();

                $crwCrewProfile->educations()->createMany($crwCrewProfileData['educations']);
                $crwCrewProfile->trainings()->createMany($crwCrewProfileData['trainings']);
                $crwCrewProfile->experiences()->createMany($crwCrewProfileData['experiences']);
                $crwCrewProfile->languages()->createMany($crwCrewProfileData['languages']);
                $crwCrewProfile->references()->createMany($crwCrewProfileData['references']);
                $crwCrewProfile->nominees()->createMany($crwCrewProfileData['nominees']);

                return response()->success('Updated successfully', $crwCrewProfile, 202);
            });
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
