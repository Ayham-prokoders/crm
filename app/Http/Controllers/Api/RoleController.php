<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Services\LmsSyncService;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = Role::find($user->current_role_id);
        if (!$currentRole->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        $query = QueryBuilder::for(Role::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['name', 'created_at']);

            $roles = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'roles' => RoleResource::collection($roles),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $roles->total(),
                'per_page' => $roles->perPage(),
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
                'links' => [
                    'first' => $roles->url(1),
                    'last' => $roles->url($roles->lastPage()),
                    'prev' => $roles->previousPageUrl(),
                    'next' => $roles->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }

    /**
     * Store a newly created role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $currentRole = Role::find($user->current_role_id);
        if (!$currentRole->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create($validatedData);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get()->pluck('id');
            $role->syncPermissions($permissions);
            LmsSyncService::syncRolePermissions($role, $permissions);

        }

        return ResponseHelper::create(new RoleResource($role));
    }

    /**
     * Display the specified role.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $user = Auth::user();
        $currentRole = Role::find($user->current_role_id);
        if (!$currentRole->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        return ResponseHelper::success(new RoleResource($role));
    }

    /**
     * Update the specified role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $user = Auth::user();
        $currentRole = Role::find($user->current_role_id);
        if (!$currentRole->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update($validatedData);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get()->pluck('id');
            $role->syncPermissions($permissions);
            LmsSyncService::syncRolePermissions($role, $permissions);

        } else {
            $role->syncPermissions([]);
            LmsSyncService::syncRolePermissions($role, []);

        }

        return ResponseHelper::success(new RoleResource($role));
    }

    /**
     * Remove the specified role.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $user = Auth::user();
        $currentRole = Role::find($user->current_role_id);
        if (!$currentRole->name=='admin') {
            return ResponseHelper::authorizationFail();
        }

        $role->delete();

        return ResponseHelper::success();
    }
}
