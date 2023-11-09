<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewDocument;
use Modules\Crew\Entities\CrwCrewDocumentRenewal;

class CrwCrewDocumentRenewalController extends Controller
{

    public function __construct(private FileUploadService $fileUpload)
    {

    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $renewData               = $request->only('issue_date', 'expire_date', 'reference_no', 'attachment', 'crw_crew_document_id');
            $renewData               = json_decode($request->get('data'), true);
            $renewData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-document');
            $crwCrewDocumentRenewal  = CrwCrewDocumentRenewal::create($renewData);

            $allCrewRenewData = CrwCrewDocumentRenewal::where('crw_crew_document_id',$renewData['crw_crew_document_id'])->get();

            return response()->success('Created successfully', $crwCrewDocumentRenewal, 202);
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
    public function update(Request $request, CrwCrewDocumentRenewal $crwCrewDocumentRenewal)
    {
        try {
            $renewData = $request->only('issue_date', 'expire_date', 'reference_no', 'attachment', 'crw_crew_document_id');

            $renewData = json_decode($request->get('data'),true);
            $renewData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-document', $crwCrewDocumentRenewal->attachment);

            $crwCrewDocumentRenewal->update($renewData);

            return response()->success('Updated successfully', $renewData, 202);
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
    public function destroy(CrwCrewDocumentRenewal $crwCrewDocumentRenewal)
    {
        try {
            $crwCrewDocumentRenewal->delete();

            return response()->success('Deleted Successfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

}
