<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewDocument;
use Modules\Crew\Entities\CrwCrewProfile;
use Modules\Crew\Http\Requests\CrwCrewDocumentRequest;
use Modules\Crew\Http\Requests\CrwCrewDocumentUpdateRequest;

class CrwCrewDocumentController extends Controller
{

    public function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:crw-document-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-document-create', ['only' => ['store']]);
        $this->middleware('permission:crw-document-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-document-delete', ['only' => ['destroy']]);
        $this->middleware('permission:crw-document-renew', ['only' => ['renewScehdules']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwCrewDocuments = CrwCrewProfile::with('crewDocuments:id,crw_crew_profile_id,document_name', 'crwCurrentRank')->globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwCrewDocuments, 200);
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
    public function store(CrwCrewDocumentRequest $request)
    {
        try {
            $documentData      = $request->only('crw_crew_profile_id', 'document_name', 'issuing_authority', 'validity_period', 'validity_period_in_month', 'business_unit');
            $renewData         = $request->only('issue_date', 'expire_date', 'reference_no', 'attachment');

            DB::transaction(function () use ($request, $documentData,$renewData)
            {
                $renewData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-document');

                $configData = Config::get('crew.crew_document_validity_period');

                if (array_key_exists($documentData['validity_period_in_month'], $configData)) {
                    $documentData['validity_period'] = $configData[$documentData['validity_period_in_month']];
                }

                $crewDocument      = CrwCrewDocument::create($documentData);
                $crewDocumentRenew = $crewDocument->crwCrewDocumentRenewals()->createMany([$renewData]);
            });

            $crwDocuments = CrwCrewDocument::with('crwCrewDocumentRenewals')->where('crw_crew_profile_id', $documentData['crw_crew_profile_id'])
                ->where('business_unit', $documentData['business_unit'])
                ->latest()->first();

            return response()->success('Updated successfully', $crwDocuments, 202);

        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwCrewDocument  $crwCrewDocument
     * @return \Illuminate\Http\Response
     */
    public function show(CrwCrewDocument $crwCrewDocument)
    {
        try {
            return response()->success('Retrieved succesfully', $crwCrewDocument->load('crwCrewDocumentRenewals'), 200);
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
     * @param  \App\Models\CrwCrewDocument  $crwCrewDocument
     * @return \Illuminate\Http\Response
     */
    public function update(CrwCrewDocumentUpdateRequest $request, CrwCrewDocument $crwCrewDocument)
    {
        try {
            $crwCrewDocument->update($request->all());

            return response()->success('Updated successfully', $crwCrewDocument, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwCrewDocument  $crwCrewDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwCrewDocument $crwCrewDocument)
    {
        try {
            $crwCrewDocument->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }   
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    public function renewScehdules(Request $request){
        try {
            $leftDays = request()->left_days ?? 30; 

            $documents = CrwCrewDocument::with('crwCrewDocumentRenewal', 'crwCrewProfile:id,full_name,pre_mobile_no,pre_email,crw_rank_id')            
            ->where('validity_period_in_month', '>', 0)
            ->get();

            $upcomingSchedules = $documents->filter(function($q) use ( $leftDays ) {
                return $q->crwCrewDocumentRenewal->expire_date < Carbon::today()->addDays( $leftDays ); 
            });

            return response()->success('Retrieved Succesfully', $upcomingSchedules, 200);
        }   
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
    
}
