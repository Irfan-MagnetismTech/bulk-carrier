<?php

namespace Modules\Administration\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\User\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class UserController extends Controller
{
    use HasApiTokens, HasRoles;

    /**
     * get all users with their roles.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        try {
//            $users = User::get()->map(function($user) {
//                $user->roles = $user->roles()->pluck('name')->first();
//                return $user;
//            });

            $users = User::with('roles')->paginate(10);

            return response()->json([
                'value'   => $users,
                'message' => 'Successfully retrieved Users',
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('administration::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request) : JsonResponse
    {
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);

            $user = User::create($input);
            $user->assignRole($request->role);
            $user->email_signature()->create([
                'user_id' => $user->id,
                'signature' => $request->email_signature,
            ]);

            return response()->json([
                'value'   => $user,
                'message' => 'User added Successfully.',
            ], 201);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param User $user
     * @return JsonResponse
     */

    public function show(User $user): JsonResponse
    {
        try
        {
            //get user role name
            $user->role = $user->roles()->pluck('id')->first();
            $user->email_signature = $user->email_signature()->first()->signature;
            return response()->json([
                'value'   => $user,
                'message' => 'Successfully retrieved User',
            ], 200);
        }
        catch (\Exception$e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

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
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user) : JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'value'   => '',
                'message' => 'User is deleted',
            ], 204);
        }
        catch (QueryException $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
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
