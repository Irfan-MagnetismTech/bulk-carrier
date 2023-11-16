<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Administration\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // if($request->isPaginate === "true"){
            //     $permissions = Permission::query()->paginate(10);
            // } else {
            //     $permissions = Permission::query()->get()->groupBy('menu')->map(function ($item, $key) {
            //         return $item->groupBy('subject');
            //     });
            // }

            $permissions = Permission::query()->globalSearch($request->all());

            return response()->success('Data list', $permissions, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }


    public function store(PermissionRequest $permissionRequest): JsonResponse
    {
        try {
            $permissions = Permission::create($permissionRequest->all());

            return response()->json([
                'value'   => $permissions,
                'message' => 'Permission added Successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission): JsonResponse
    {
        try {
            $permission['subject_type'] = base64_encode(get_class($permission));

            return response()->json([
                'value'   => $permission,
                'message' => 'Successfully retrieved Permission',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        try {
            $permission->update($request->all());

            return response()->json([
                'value'   => $permission,
                'message' => 'Permission updated Successfully.',
            ], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();

            return response()->json([
                'value'   => '',
                'message' => 'Permission deleted Successfully.',
            ], 204);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
