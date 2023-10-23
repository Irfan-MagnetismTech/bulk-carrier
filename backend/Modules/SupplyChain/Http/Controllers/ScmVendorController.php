<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SupplyChain\Entities\ScmVendor;
use Modules\SupplyChain\Http\Requests\ScmVendorRequest;

class ScmVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scm_vendors = ScmVendor::with('scmVendorContactPersons')->latest()->paginate(10);

            return response()->success('Data list', $scm_vendors, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmVendorRequest $request)
    {
        try {
            DB::beginTransaction();
            $scm_vendor = ScmVendor::create($request->all());
            $scm_vendor->scmVendorContactPersons()->createMany($request->scmVendorContactPersons);
            DB::commit();

            return response()->success('Data created succesfully', $scm_vendor, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmVendor $vendor
     * @return JsonResponse
     */
    public function show(ScmVendor $vendor): JsonResponse
    {
        try {
            return response()->success('data', $vendor->load('scmVendorContactPersons'), 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmVendorRequest $request
     * @param ScmVendor $vendor
     * @return JsonResponse
     */
    public function update(ScmVendorRequest $request, ScmVendor $vendor)
    {
        try {
            $vendor->update($request->all());

            $vendor->scmVendorContactPersons()->createMany($request->scmVendorContactPersons);

            return response()->success('Data updated sucessfully!', $vendor, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmVendor $vendor
     * @return JsonResponse
     */
    public function destroy(ScmVendor $vendor): JsonResponse
    {
        try {
            $vendor->scmVendorContactPersons()->delete();
            $vendor->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    public function searchVendor(Request $request)
    {
        $vendor = ScmVendor::query()
            ->with('scmVendorContactPersons')
            ->where('name', 'like', "%$request->searchParam%")
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $vendor, 200);
    }
}
