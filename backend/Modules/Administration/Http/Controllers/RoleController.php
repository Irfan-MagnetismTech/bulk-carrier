<?php

namespace Modules\Administration\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Administration\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class RoleController extends Controller
{
    use HasRoles;

    public function index() : JsonResponse
    {
        try {

            $roles = Role::with('permissions')->orderBy('name', 'ASC')->get();

            return response()->json([
                'value'   => $roles,
                'message' => 'Roles retrieved Successfully.',
            ], 200);
        }
        catch (\Exception$e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

    }

    public function store(RoleRequest $request) : JsonResponse
    {
        try {

            $role = Role::create(['name'=>$request->name]);
            $role->syncPermissions([$request->current_permissions]);
            return response()->json([
                'value'   => $role,
                'message' => 'Role added Successfully.',
            ], 201);
        }
        catch (\Exception$e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Role $role) : JsonResponse
    {

        try {
            $role['current_permissions'] = $role->permissions->pluck('id');

            return response()->json([
                'value' => $role,
                'message' => 'Successfully retrieved role and permission.'
            ], 200);
        } catch (\Exception $e){
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Role $role) : JsonResponse
    {
        try {

            $role->update(['name'=>$request->name]);
            $role->syncPermissions($request->current_permissions);

            return response()->json([
                'value'   => $role,
                'message' => 'Role updated Successfully.',
            ], 201);
        }
        catch (\Exception$e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Role $role): JsonResponse
    {
        try {
            $role->delete();

            return response()->json([
                'value'   => '',
                'message' => 'Role deleted Successfully.',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
