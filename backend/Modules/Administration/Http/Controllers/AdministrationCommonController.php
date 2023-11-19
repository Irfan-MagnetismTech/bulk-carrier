<?php

namespace Modules\Administration\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AdministrationCommonController extends Controller
{
    public function getUsers(Request $request)
    {
        try {

            $users = User::when(request()->business_unit != "ALL", function ($q) {
                $q->where('business_unit', request()->business_unit);
            })
            ->when(request()->name, function($q) {
                $q->where('name', 'like', '%' . request()->name . '%');
            })
            ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $users,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getRoles()
    {

        try {

            $roles = Role::when(request()->name, function($q) {
                    $q->where('name', 'like', '%' . request()->name . '%');
                })
                ->get();

            return response()->json([
                'status' => 'success',
                'value'  => $roles,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getPermissions(Request $request)
    {
        try {

            $permissions = Permission::get(['id', 'name', 'subject', 'menu']);

            $customPermissions = $permissions->groupBy('menu')->map(function ($menu)
            {
                $subjects = $menu->flatten()->groupBy('subject')->map(function ($subject)
                {
                    $items = $subject->map(function ($item)                     {
                        $item['is_checked'] = false;

                        return $item;
                    });
                    $items['is_checked'] = false;

                    return $items;
                });
                $subjects['is_checked'] = false;

                return $subjects;
            });

            return response()->json([
                'status' => 'success',
                'value'  => $customPermissions,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

}
