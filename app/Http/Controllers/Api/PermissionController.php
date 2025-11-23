<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!$role->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        $query = QueryBuilder::for(Permission::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['name', 'created_at']);


            $permissions = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'permissions' => PermissionResource::collection($permissions),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $permissions->total(),
                'per_page' => $permissions->perPage(),
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'from' => $permissions->firstItem(),
                'to' => $permissions->lastItem(),
                'links' => [
                    'first' => $permissions->url(1),
                    'last' => $permissions->url($permissions->lastPage()),
                    'prev' => $permissions->previousPageUrl(),
                    'next' => $permissions->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!$role->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::invalidData();
        }

        $actions = ['create', 'update', 'read', 'delete' ,'all'];
        foreach ($actions as $action) {
            $name =   $request->name . "-{$action}";
            $permissions[] = Permission::create(['name' => $name]);
        }


        return ResponseHelper::success(PermissionResource::collection($permissions));
    }

    /**
     * Display the specified permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!$role->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        return ResponseHelper::success(new PermissionResource($permission));
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!$role->name=='admin') {
            return ResponseHelper::authorizationFail();
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        if ($validator->fails()) {
            return ResponseHelper::invalidData();
        }

        $permission->update(['name' => $request->name]);

        return ResponseHelper::success(new PermissionResource($permission));
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!$role->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        if (!$permission) {
            return ResponseHelper::DataNotFound();
        }

        $permission->delete();

        return ResponseHelper::success();
    }
}
