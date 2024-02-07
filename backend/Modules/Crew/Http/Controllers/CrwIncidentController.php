<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwIncident;
use App\Services\FileUploadService;
use Modules\Crew\Http\Requests\CrwIncidentRequest;

class CrwIncidentController extends Controller
{
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
            $crwIncidents = CrwIncident::with('crwIncidentParticipants.crwCrewProfile','opsVessel')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwIncidents, 200);
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
    public function store(CrwIncidentRequest $request)
    {
        try {
            DB::transaction(function () use ($request)
            {
                $crwIncidentData = $request->only('ops_vessel_id', 'date_time', 'type', 'location', 'reported_by', 'description', 'business_unit');
                $crwIncidentData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-incident');

                $crwIncident     = CrwIncident::create($crwIncidentData);
                $crwIncident->crwIncidentParticipants()->createMany($request->crwIncidentParticipants);
            });

            return response()->success('Created Successfully', [], 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwIncident  $crwIncident
     * @return \Illuminate\Http\Response
     */
    public function show(CrwIncident $crwIncident)
    {
        try {
            return response()->success('Retrieved successfully', $crwIncident->load('crwIncidentParticipants.crwCrewProfile','opsVessel:id,name'), 200);
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
     * @param  \App\Models\CrwIncident  $crwIncident
     * @return \Illuminate\Http\Response
     */
    public function update(CrwIncidentRequest $request, CrwIncident $crwIncident)
    {
        try {
            DB::transaction(function () use ($request, $crwIncident)
            {
                $crwIncidentData = $request->only('ops_vessel_id', 'date_time', 'type', 'location', 'reported_by', 'description', 'business_unit');
                $crwIncidentData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-incident', $crwIncident->attachment);

                $crwIncident->update($crwIncidentData);
                $crwIncident->crwIncidentParticipants()->delete();
                $crwIncident->crwIncidentParticipants()->createMany($request->crwIncidentParticipants);
            });
            
            return response()->success('Updated successfully', $crwIncident, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwIncident  $crwIncident
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwIncident $crwIncident)
    {
        try {
            $crwIncident->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
