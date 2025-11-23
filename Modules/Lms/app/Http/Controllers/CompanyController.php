<?php

namespace Modules\Lms\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Lms\Models\Company;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Http\Requests\CompanyRequest;
use Modules\Lms\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     *
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
         $role = Role::find($currentRole);

        // $query = QueryBuilder::for(Company::class)
        //     ->allowedFilters(['name'])
        //     ->allowedSorts(['name', 'created_at']);

        $query = QueryBuilder::for(Company::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['name', 'created_at'])
            ->with('users'); 

        if ($role->name == 'companySupervisor') {
            $query->where('id', $user->company_id);
        } elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $compaines = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'compaines' => CompanyResource::collection($compaines),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $compaines->total(),
                'per_page' => $compaines->perPage(),
                'current_page' => $compaines->currentPage(),
                'last_page' => $compaines->lastPage(),
                'from' => $compaines->firstItem(),
                'to' => $compaines->lastItem(),
                'links' => [
                    'first' => $compaines->url(1),
                    'last' => $compaines->url($compaines->lastPage()),
                    'prev' => $compaines->previousPageUrl(),
                    'next' => $compaines->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }


    /**
     * Store a newly created company in storage.
     *
     */
    public function store(CompanyRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }


        $company = Company::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'bill_email' => $request->input('bill_email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        return ResponseHelper::create(new CompanyResource($company));
    }

    /**
     * Display the specified company.
     *
     */
    public function show(Company $company)
    {

        return ResponseHelper::success(new CompanyResource($company));
    }

    /**
     * Update the specified company in storage.
     *
     */
    public function update(CompanyRequest $request, Company $company)
    {
        // $user = Auth::user();
        // $currentRole = $user->current_role_id;
        // $role = Role::find($currentRole);

        // if (!in_array($role->name, ['admin', 'supervisor'])) {
        //     return ResponseHelper::authorizationFail();
        // }

        $company->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'bill_email' => $request->input('bill_email'),
            'phone' => $request->input('phone'),
        ]);
        return ResponseHelper::success(new CompanyResource($company));
    }

    /**
     * Remove the specified company from storage.
     *
     */
    public function destroy(Company $company)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $company->delete();
        return ResponseHelper::success();
    }
}
