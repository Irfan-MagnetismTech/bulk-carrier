<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwRecruitmentApproval;

class CrwRecruitmentApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwRecruitmentApprovals = CrwRecruitmentApproval::with('crwRecruitmentApprovalLines')->get();

            return response()->success('Retrieved Succesfully', $crwRecruitmentApprovals, 200);
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
                $crwRecruitmentApprovalData = $request->only('applied_date', 'page_title', 'subject', 'total_approved', 'crew_agreed_to_join', 'crew_selected', 'crew_panel', 'crew_rest', 'body', 'remarks', 'business_unit');
                $crwRecruitmentApproval     = CrwRecruitmentApproval::create($crwRecruitmentApprovalData);
                $crwRecruitmentApproval->crwRecruitmentApprovalLines()->createMany($request->crwRecruitmentApprovalLines);

                return response()->success('Created Succesfully', $crwRecruitmentApproval, 201);
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
     * @param  \App\Models\CrwRecruitmentApproval  $crwRecruitmentApproval
     * @return \Illuminate\Http\Response
     */
    public function show(CrwRecruitmentApproval $crwRecruitmentApproval)
    {
        try {
            return response()->success('Retrieved succesfully', $crwRecruitmentApproval->load('crwRecruitmentApprovalLines'), 200);
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
     * @param  \App\Models\CrwRecruitmentApproval  $crwRecruitmentApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwRecruitmentApproval $crwRecruitmentApproval)
    {
        try {
            DB::transaction(function () use ($request, $crwRecruitmentApproval)
            {
                $crwRecruitmentApprovalData = $request->only('applied_date', 'page_title', 'subject', 'total_approved', 'crew_agreed_to_join', 'crew_selected', 'crew_panel', 'crew_rest', 'body', 'remarks', 'business_unit');
                $crwRecruitmentApproval->update($crwRecruitmentApprovalData);
                $crwRecruitmentApproval->crwRecruitmentApprovalLines()->delete();
                $crwRecruitmentApproval->crwRecruitmentApprovalLines()->createMany($request->crwRecruitmentApprovalLines);

                return response()->success('Updated succesfully', $crwRecruitmentApproval, 202);
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
     * @param  \App\Models\CrwRecruitmentApproval  $crwRecruitmentApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwRecruitmentApproval $crwRecruitmentApproval)
    {
        try {
            $crwRecruitmentApproval->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
