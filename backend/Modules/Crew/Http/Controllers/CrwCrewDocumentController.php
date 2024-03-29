<?php

namespace Modules\Crew\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Crew\Entities\CrwCrewDocument;

class CrwCrewDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $crwCrewDocuments = CrwCrewDocument::with('crwCrewDocumentRenewals')->when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);  
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwCrewDocuments, 200);
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
                $crwCrewDocumentData = $request->only('crw_crew_id', 'reference_no', 'name', 'issuing_authority', 'validity_period', 'business_unit');
                $crwCrewDocument     = CrwCrewDocument::create($crwCrewDocumentData);
                $crwCrewDocument->crwCrewDocumentRenewals()->createMany($request->crwCrewDocumentRenewals);

                return response()->success('Created Succesfully', $crwCrewDocument, 201);
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
            DB::transaction(function () use ($request, $crwCrewDocument)
            {
                $crwCrewDocumentData = $request->only('crw_crew_id', 'reference_no', 'name', 'issuing_authority', 'validity_period', 'business_unit');
                $crwCrewDocument->update($crwCrewDocumentData);
                $crwCrewDocument->crwCrewDocumentRenewals()->delete();
                $crwCrewDocument->crwCrewDocumentRenewals()->createMany($request->crwCrewDocumentRenewals);

                return response()->success('Updated succesfully', $crwCrewDocument, 202);
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
