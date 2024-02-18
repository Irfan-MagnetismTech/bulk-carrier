<?php

namespace Modules\Crew\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Crew\Entities\CrwPolicy;
use Modules\Crew\Http\Requests\CrwPolicyRequest;

class CrwPolicyController extends Controller
{
    /**
     * @param FileUploadService $fileUpload
     */
    public function __construct(private FileUploadService $fileUpload)
    {
        $this->middleware('permission:crw-policy-view', ['only' => ['index', 'show']]);
        $this->middleware('permission:crw-policy-create', ['only' => ['store']]);
        $this->middleware('permission:crw-policy-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:crw-policy-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $crwPolicies = CrwPolicy::globalSearch($request->all());

            return response()->success('Retrieved Successfully', $crwPolicies, 200);
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
    public function store(CrwPolicyRequest $request)
    {
        try {
            $crwPolicyData               = $request->only('name', 'type', 'business_unit');
            $crwPolicyData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/policy');

            $crwPolicy = CrwPolicy::create($crwPolicyData);

            return response()->success('Created Successfully', $crwPolicy, 201);
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
            return response()->success('Retrieved Successfully', $crwPolicy, 200);
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
    public function update(CrwPolicyRequest $request, CrwPolicy $crwPolicy)
    {
        try {
            $crwPolicyData               = $request->only('name', 'type', 'business_unit');
            $crwPolicyData['attachment'] = $this->fileUpload->handleFile($request->attachment, 'crw/policy', $crwPolicy->attachment);

            $crwPolicy->update($crwPolicyData);

            return response()->success('Updated Successfully', $crwPolicy, 202);
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
