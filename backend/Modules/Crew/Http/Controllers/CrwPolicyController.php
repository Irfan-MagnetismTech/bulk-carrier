<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Crew\Entities\CrwPolicy;

class CrwPolicyController extends Controller
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
    public function index()
    {
        try {
            $crwPolicies = CrwPolicy::when(request()->business_unit != "ALL", function($q){
                $q->where('business_unit', request()->business_unit);
            })->paginate(10);

            return response()->success('Retrieved Succesfully', $crwPolicies, 200);
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
            $crwPolicyData               = $request->only('name', 'type', 'business_unit');
            $crwPolicyData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/policy');

            $crwPolicy = CrwPolicy::create($crwPolicyData);

            return response()->success('Created Succesfully', $crwPolicy, 201);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrwPolicy  $crwPolicy
     * @return \Illuminate\Http\Response
     */
    public function show(CrwPolicy $crwPolicy)
    {
        try {
            return response()->success('Retrieved succesfully', $crwPolicy, 200);
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
     * @param  \App\Models\CrwPolicy  $crwPolicy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrwPolicy $crwPolicy)
    {
        try {
            $crwPolicyData               = $request->only('name', 'type', 'business_unit');
            $crwPolicyData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/policy', $crwPolicy->attachment);

            $crwPolicy->update($crwPolicyData);

            return response()->success('Updated succesfully', $crwPolicy, 202);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrwPolicy  $crwPolicy
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrwPolicy $crwPolicy)
    {
        try {
            $crwPolicy->delete();

            return response()->success('Deleted Succesfully', null, 204);
        }
        catch (QueryException $e)
        {
            return response()->error($e->getMessage(), 500);
        }
    }
}
