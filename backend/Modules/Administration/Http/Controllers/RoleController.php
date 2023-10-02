<?php

namespace Modules\Administration\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class RoleController extends Controller
{
    use HasRoles;

    /**
     * get all users with their roles.
     * @return JsonResponse
     */
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

    public function store(Request $request) : JsonResponse
    {
        try {
            $this->valida
        }
        catch (\Exception$e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('administration::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('administration::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function getCurrentUser(Request $request){
        $user = $request->user();
        $user['role'] = $request->user()->roles()->pluck('name')->first();
        $user['permissions'] = $request->user()->getPermissionsViaRoles()->pluck('name')->toArray();
        $user['permissions'] = array_merge($user['permissions'], ['dashboard']);
        $user['port'] = $request->user()->port;
        return $user;
    }
}
