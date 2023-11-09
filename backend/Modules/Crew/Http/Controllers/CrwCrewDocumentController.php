<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewDocument;

class CrwCrewDocumentController extends Controller
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
            $crwCrewDocuments = CrwCrewDocument::when(request()->business_unit != 'ALL', function ($q)
            {
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);

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
    public function store(Request $request)
    {
        try {
            $documentData      = $request->only('crw_crew_id', 'name', 'issuing_authority', 'validity_period', 'validity_period_in_month', 'business_unit');
            $renewData         = $request->only('issue_date', 'expire_date', 'reference_no', 'attachment');

            $documentData = json_decode($request->get('data'),true);
            $renewData = json_decode($request->get('data'),true);

            DB::transaction(function () use ($request,$documentData,$renewData)
            {
                $renewData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/crew-document');

                $configData = Config::get('crew.crew_document_validity_period');

                if (array_key_exists($documentData['validity_period_in_month'], $configData)) {
                    $documentData['validity_period'] = $configData[$documentData['validity_period_in_month']];
                }

                $crewDocument      = CrwCrewDocument::create($documentData);
                $crewDocumentRenew = $crewDocument->crwCrewDocumentRenewals()->createMany([$renewData]);
            });

            $crwDocuments = CrwCrewDocument::with('crwCrewDocumentRenewals')->where('crw_crew_id', $documentData['crw_crew_id'])
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
    public function update(Request $request, CrwCrewDocument $crwCrewDocument)
    {
        try {

            $crwCrewDocumentData = $request->only('crw_crew_id', 'name', 'issuing_authority', 'validity_period', 'validity_period_in_month', 'business_unit');

            $configData = Config::get('crew.crew_document_validity_period');
            if (array_key_exists($crwCrewDocumentData['validity_period_in_month'], $configData)) {
                $crwCrewDocumentData['validity_period'] = $configData[$crwCrewDocumentData['validity_period_in_month']];
            }

            $crwCrewDocument->update($crwCrewDocumentData);

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
}
