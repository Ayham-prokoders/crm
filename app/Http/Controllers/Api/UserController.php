<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\UserCreatedMail;
use Illuminate\Validation\Rule;
use App\Services\LmsSyncService;
use App\Http\Filters\FiltersName;
use App\Http\Filters\FiltersRole;
use App\Models\Role;
use App\Events\UserAssignedToClass;
use App\Http\Helper\ResponseHelper;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Modules\TrainerManagement\Models\Instructor;
use App\Http\Resources\RoleResource;
class UserController extends Controller
{
    public function index(Request $request)
    {
        // Check if the authenticated user is an admin
        $currentUser = Auth::user();
        $currentRole = Role::find($currentUser->current_role_id);

        if (!in_array($currentRole->name, ['admin', 'supervisor', 'companySupervisor','accreditation_manager'])) {
            return ResponseHelper::authorizationFail();
        }

        $query = QueryBuilder::for(User::class)
                ->allowedFilters([
                    AllowedFilter::custom('name', new FiltersName()),
                    AllowedFilter::partial('email'),
                    AllowedFilter::custom('role', new FiltersRole()),
                ])
                ->allowedSorts(['name', 'email']);

            if ($currentRole->name === 'companySupervisor') {
                $query->where('company_id', $currentUser->company_id);
            }

            $users = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'users' => UserResource::collection($users),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'links' => [
                    'first' => $users->url(1),
                    'last' => $users->url($users->lastPage()),
                    'prev' => $users->previousPageUrl(),
                    'next' => $users->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $currentRole = Role::find($currentUser->current_role_id);

        if (!in_array($currentRole->name, ['admin','supervisor','companySupervisor','accreditation_manager'])) {
            return ResponseHelper::authorizationFail();
        }

        if (User::where('email', $request->email)->exists()) {
            return ResponseHelper::AlreadyExists( 'The provided email is already exists. Please use a different email.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'salutation'=>'nullable|string',
            'nationality'=>'nullable|string'
        ]);

        $path = '';
        if ($request->file('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->move('profile_images', $name);
        }

        // Determine the company ID and role
        $company_id = $currentRole->name === 'companySupervisor' ? $currentUser->company_id : $request->company;
        $role_name = $currentRole->name === 'companySupervisor' ? 'trainee' : $request->role;

        $role = Role::where('name', $role_name)->first();
        if (!$role) {
            return ResponseHelper::DataNotFound();
        }

        $user = User::create([
            'name' => $request->name,
            'middel_name' => $request->middel_name ?? null,
            'last_name' => $request->last_name ?? null,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone ?? null,
            'company_id' => $company_id,
            'image' => $path,
            'salutation'=>$request->salutation,
            'nationality'=>$request->nationality,
            'city'=>$request->input('city'),
            'job'=>$request->job
        ]);

        // Assign the role to the user
        $user->assignRole($role);
        $user->update(['role' => $role->id, 'current_role_id' => $role->id]);

        LmsSyncService::syncUserRoles($user);

        //Create instrucor profile for trainer
        $trainerLink = null;
        if ($role_name === 'trainer') {
            Instructor::create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name . '-' . $user->id),
            ]);
            $trainerLink = env('PROJECT_FRONTEND_LMS').'/apps/instructor-profile/';
            $user->removeRole($role);
            $role1 = Role::where('name', 'pre_trainer')->first();
            $user->assignRole($role1);
        }
        if ($role_name === 'trainee') {
            $trainerLink = env('PROJECT_FRONTEND_LMS');
        }
        // Trigger the event to send notification
        event(new UserAssignedToClass($user));

        // Send an email notification
        Mail::to($user->email)->queue(new UserCreatedMail($user, $request->password, $trainerLink));

        return ResponseHelper::create(new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Check if the authenticated user is an admin
        $currentUser = Auth::user();
        $currentRole = Role::find($currentUser->current_role_id);
        // if (!in_array($currentRole->name, ['admin', 'supervisor'])) {
        //     return ResponseHelper::authorizationFail();
        // }

        return ResponseHelper::success(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $currentRole = Role::find($currentUser->current_role_id);

        if (!in_array($currentRole->name, ['admin', 'supervisor', 'companySupervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        // If companySupervisors update users within their company and role trainee
        if ($currentRole->name === 'companySupervisor') {
            if ($user->company_id !== $currentUser->company_id || $user->role !== Role::where('name', 'trainee')->first()->id) {
                return ResponseHelper::authorizationFail();
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'salutation'=>'nullable|string',
            'nationality'=>'nullable|string'
        ]);

        $path = $user->image;

        if ($request->file('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $path = $file->move('profile_images', $name);
        }

        // Determine the role and company (for companySupervisor)
        $role_name = $currentRole->name === 'companySupervisor' ? 'trainee' : $request->role;
        $role = Role::where('name', $role_name)->first();

        if (!$role) {
            return ResponseHelper::DataNotFound();
        }

        $company_id = $currentRole->name === 'companySupervisor' ? $currentUser->company_id : $request->company;

        $user->update([
            'name' => $request->name,
            'middel_name' => $request->middel_name ?? $user->middel_name,
            'last_name' => $request->last_name ?? $user->last_name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'phone' => $request->phone ?? $user->phone,
            'company_id' => $company_id,
            'image' => $path,
            'salutation'=>$request->salutation,
            'nationality'=>$request->nationality,
            'city'=>$request->input('city'),
            'job'=>$request->job
        ]);

        // Update the user's role
        $user->syncRoles($role);
        $user->update(['role' => $role->id, 'current_role_id' => $role->id]);

        LmsSyncService::sync($user, 'update');
        LmsSyncService::syncUserRoles($user);
        if ($role_name === 'trainer') {
            $existingInstructor = Instructor::where('user_id', $user->id)->first();

            if (!$existingInstructor) {
                Instructor::create([
                    'user_id' => $user->id,
                    'slug' => Str::slug($user->name . '-' . $user->id),
                ]);
            }
        }

        // Trigger the event
        event(new UserAssignedToClass($user));

        return ResponseHelper::success(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ResponseHelper::success();
    }

    public function setCurrentRole(Request $request)
    {
         // Validate the incoming request data
        $validatedData = $request->validate([
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) {
                    // Check if the authenticated user has the provided role_id
                    $query->whereIn('id', Auth::user()->roles->pluck('id'));
                }),
            ],
        ]);
       // Update the current_role_id attribute for the authenticated user
        Auth::user()->update(['current_role_id' => $validatedData['role_id']]);
        return ResponseHelper::success(new UserResource(Auth::user()));
    }

    public function allUserRoles(Request $request){
       $roles=  Auth::user()->roles;

       return ResponseHelper::success(RoleResource::collection($roles));
    }
}
