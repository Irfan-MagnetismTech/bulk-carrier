<?php

namespace Modules\SupplyChain\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\SupplyChain\Entities\ScmService;
use Modules\SupplyChain\Http\Requests\ScmServiceRequest;

class ScmServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $scm_services = ScmService::latest()->paginate(10);

            return response()->success('Data list', $scm_services, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store(ScmServiceRequest $request): JsonResponse
    {
        try {
            $scm_services = ScmService::create($request->all());

            return response()->success('Data created succesfully', $scm_services, 201);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     * @param ScmService $service
     * @return JsonResponse
     */
    public function show(ScmService $service): JsonResponse
    {
        try {
            return response()->success('data', $service, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ScmServiceRequest $request
     * @param ScmService $service
     * @return JsonResponse
     */
    public function update(ScmServiceRequest $request, ScmService $service): JsonResponse
    {
        try {
            $service->update($request->all());

            return response()->success('Data updated sucessfully!', $service, 202);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param ScmService $service
     * @return JsonResponse
     */
    public function destroy(ScmService $service): JsonResponse
    {
        try {
            $service->delete();

            return response()->success('Data deleted sucessfully!', null,  204);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * Search for services based on the provided search parameter.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function searchService(Request $request): JsonResponse
    {
        $materialCategory = ScmService::query()
            ->where('name', 'like', "%{$request->searchParam}%")
            ->orderByDesc('name')
            ->limit(10)
            ->get();

        return response()->success('Search result', $materialCategory, 200);
    }
}
