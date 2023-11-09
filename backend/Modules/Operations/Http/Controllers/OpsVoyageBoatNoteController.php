<?php

namespace Modules\Operations\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\FileUploadService;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Support\Renderable;
use Modules\Operations\Entities\OpsVoyageBoatNote;
use Modules\Operations\Http\Requests\OpsVoyageBoatNoteRequest;

class OpsVoyageBoatNoteController extends Controller
{
   // use HasRoles;
   
   function __construct(private FileUploadService $fileUpload)
   {
   //     $this->middleware('permission:voyage-boat-note-create|voyage-boat-note-edit|voyage-boat-note-show|voyage-boat-note-delete', ['only' => ['index','show']]);
   //     $this->middleware('permission:voyage-boat-note-create', ['only' => ['store']]);
   //     $this->middleware('permission:voyage-boat-note-edit', ['only' => ['update']]);
   //     $this->middleware('permission:voyage-boat-note-delete', ['only' => ['destroy']]);
   }
   /**
    * get all users with their roles.
    * @param Request $request
    * @return JsonResponse
    */
    public function index()
    {
        try {
            $voyage_boat_notes = OpsVoyageBoatNote::with('opsVessel','opsVoyage','opsVoyageBoatNoteLines')->latest()->paginate(15);
            
            return response()->success('Successfully retrieved voyage boat notes.', $voyage_boat_notes, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
 

       /**
     * Store a newly created resource in storage.
     * 
     * @param OpsVoyageBoatNoteRequest $request
     * @return JsonResponse
    */
    public function store(OpsVoyageBoatNoteRequest $request): JsonResponse
    {
        $opsVoyageBoatNoteLines = collect($request->opsVoyageBoatNoteLines);
        
        // dd($opsVoyageBoatNoteLines);
        try {
            DB::beginTransaction();
            $voyageBoatNoteInfo = $request->except(
                '_token',
                'opsVoyageBoatNoteLines',
            );

            $opsVoyageBoatNoteLines = $opsVoyageBoatNoteLines->map(function($boat_note, $index) use ($request) {
                $attachment_path = 'null';
                
                if(isset($request->attachment[$index]) && $request->attachment[$index] != null){
                    $attachment_path = $this->fileUpload->handleFile($request->attachment[$index], 'ops/voyage/boat_note_line');
                }
            
                $boat_note['attachment'] = $attachment_path;
                return $boat_note;
            });

            $voyageBoatNote = OpsVoyageBoatNote::create($voyageBoatNoteInfo);
            $voyageBoatNote->opsVoyageBoatNoteLines()->createMany($opsVoyageBoatNoteLines);
            DB::commit();
            return response()->success('Voyage boat note added successfully.', $voyageBoatNote, 201);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }


    /**
     * Display the specified maritime certification.
     *
     * @param  OpsVoyageBoatNote  $maritime_certification
     * @return JsonResponse
     */
    public function show(OpsVoyageBoatNote $voyage_boat_note): JsonResponse
    {
        $voyage_boat_note->load('opsVessel','opsVoyage','opsVoyageBoatNoteLines');
        try
        {
            return response()->success('Successfully retrieved voyage boat note.', $voyage_boat_note, 200);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param OpsVoyageBoatNoteRequest $request
     * @param  OpsVoyageBoatNote  $voyage_boat_note
     * @return JsonResponse
     */
    public function update(OpsVoyageBoatNoteRequest $request, OpsVoyageBoatNote $voyage_boat_note): JsonResponse
    {
        try {
            DB::beginTransaction();
            $voyageBoatNoteInfo = $request->except(
                '_token',
                'opsVoyageBoatNoteLines',
            );
            $voyage_boat_note->load('opsVoyageBoatNoteLines');
            
            $voyage_boat_note->update($voyageBoatNoteInfo);           
            $boat_note_lines= $this->fileUpload->handleMultipleFiles('ops/voyage/boat_note_line',$request->opsVoyageBoatNoteLines,$voyage_boat_note->opsVoyageBoatNoteLines);
            $voyage_boat_note->opsVoyageBoatNoteLines()->delete();
            $voyage_boat_note->opsVoyageBoatNoteLines()->createMany($boat_note_lines);
            DB::commit();
            return response()->success('Vessel particular updated successfully.', $voyage_boat_note, 202);
        }
        catch (QueryException $e)
        {
            DB::rollBack();
            return response()->error($e->getMessage(), 500);
        }
    }

        /**
     * Remove the specified vessel from storage.
     *
     * @param  OpsVoyageBoatNote  $voyage_boat_note
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpsVoyageBoatNote $voyage_boat_note): JsonResponse
    {
        try
        {
            $voyage_boat_note->load('opsVoyageBoatNoteLines');
            foreach($voyage_boat_note->opsVoyageBoatNoteLines as $boat_note_line){
                $this->fileUpload->deleteFile($boat_note_line->attachment);
            }

            $voyage_boat_note->opsVoyageBoatNoteLines()->delete();
            $voyage_boat_note->delete();

            return response()->json([
                'message' => 'Successfully deleted voyage boat note.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }



}
