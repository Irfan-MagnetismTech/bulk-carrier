<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Administration\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Role; 

class RoleController extends Controller
{
    use HasRoles;

    public function index(Request $request): JsonResponse
    {
        try {
            $roles = Role::with('permissions')->globalSearch($request->all());

            return response()->success('Data list', $roles, 200);
        } catch (\Exception $e) {

            return response()->error($e->getMessage(), 500);
        }
    }

    /**
     * @param RoleRequest $request
     */
    public function store(RoleRequest $request): JsonResponse
    {
        try {
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions([$request->current_permissions]);

            return response()->json([
                'value'   => $role,
                'message' => 'Role added Successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param Role $role
     * @return mixed
     */
    public function show(Role $role)
    {
        $permissions = Permission::get(['id', 'name', 'subject', 'menu']);

        $usersPermissions = $permissions->groupBy('menu')->map(function ($menu) use ($role)
        {
            $subjects = $menu->flatten()->groupBy('subject')->map(function ($subject) use ($role)
            {
                $items = $subject->map(function ($item) use ($role)
                {
                    $is_checked         = $role->permissions->pluck('id')->contains($item?->id);
                    $item['is_checked'] = $is_checked;

                    return $item;
                });

                $givenPermissions    = $subject->pluck('id')->intersect($role->permissions->pluck('id'));
                $allAccess           = count($givenPermissions) < count($subject->pluck('id')) ? false : true;
                $items['is_checked'] = $allAccess;

                return $items;
            });

            $givenPermissions       = $menu->pluck('id')->intersect($role->permissions->pluck('id'));
            $allAccess              = count($givenPermissions) < count($menu->pluck('id')) ? false : true;
            $subjects['is_checked'] = $allAccess;

            return $subjects;
        });

        try {
            $newData = [
                'id'          => $role->id,
                'name'        => $role->name,
                'guard_name'  => $role->guard_name,
                'permissions' => $usersPermissions,
            ];

            return response()->json([
                'value'   => $newData,
                'message' => 'Successfully retrieved role and permission.',
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @param Role $role
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        try {

            $role->update(['name' => $request->name]);
            $role->syncPermissions($request->current_permissions);

            return response()->json([
                'value'   => $role,
                'message' => 'Role updated Successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param Role $role
     */
    public function destroy(Role $role): JsonResponse
    {
        try {
            $role->delete();

            return response()->json([
                'value'   => '',
                'message' => 'Role deleted Successfully.',
            ], 204);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
