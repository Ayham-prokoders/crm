<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InvitationEmail;
use App\Services\LmsSyncService;
use Modules\Lms\Models\{Classe};
use App\Models\{Invitation,User};
use App\Models\Role;
use App\Events\UserAssignedToClass;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\InvitationResource;
use App\Notifications\AssignedToClassNotification;

class InvitationController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }

        $query = QueryBuilder::for(Invitation::class)
            ->allowedFilters(['email'])
            ->allowedSorts(['email', 'created_at']);

            $invitations = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'invitations' => InvitationResource::collection($invitations),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $invitations->total(),
                'per_page' => $invitations->perPage(),
                'current_page' => $invitations->currentPage(),
                'last_page' => $invitations->lastPage(),
                'from' => $invitations->firstItem(),
                'to' => $invitations->lastItem(),
                'links' => [
                    'first' => $invitations->url(1),
                    'last' => $invitations->url($invitations->lastPage()),
                    'prev' => $invitations->previousPageUrl(),
                    'next' => $invitations->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $currentRole = $currentUser->current_role_id;
        $role = Role::find($currentRole);
        $classId = $request->input('class_id');

       if (!in_array($role->name, ['admin', 'supervisor'])) {
           return ResponseHelper::authorizationFail();
       }
        $class = Classe::findOrFail($classId);

        if ($class->course_type === 'custom') {
                $request->validate([
                    'user_ids' => 'required|array',
                    'user_ids.*' => 'exists:users,id'
                ]);

                $userIds = $request->input('user_ids');

                foreach ($userIds as $userId) {
                    $user = User::find($userId);

                    if ($user && !$user->trainees()->where('class_id', $classId)->exists()) {
                        $user->trainees()->attach($classId);
                        $user->assignRole('trainee');
                        $user->save();

                        dispatch(function () use ($user, $classId) {
                            LmsSyncService::syncTraineeClasses($user, $classId);
                        })->afterResponse();

                        $user->notify(new AssignedToClassNotification($user->id));
                        event(new UserAssignedToClass($user));
                    }
                }

                return ResponseHelper::success('Users assigned successfully to custom class.');

            } else {

                $request->validate([
                    'emails' => 'required|array',
                    'emails.*' => 'required|email',
                ]);

                $emails = $request->input('emails');
                $invitationsSent = [];

                foreach ($emails as $email) {
                    // Check if the email already has an account
                    $user = User::where('email', $email)->first();
                    if ($user) {
                        // Check if the user is not already registered in the class
                        if (!$user->trainees()->where('class_id', $request->input('class_id'))->exists()) {
                            // Register user in class
                            $user->trainees()->attach($request->input('class_id'));
                            $user->assignRole('trainee');
                            $user->save();
                            dispatch(function () use ($user, $classId) {
                                LmsSyncService::syncTraineeClasses($user, $classId);
                            })->afterResponse();
                            $user->notify(new AssignedToClassNotification($user->id));
                            event(new UserAssignedToClass($user));

                        }

                    } else {
                        // Check if an invitation has already been sent to this email
                        if (!Invitation::where('email', $email)->where('classe_id',$request->input('class_id'))->exists()) {
                            // Generate invitation token and create invitation record
                            $token = Str::random(60);
                            $invitation = Invitation::create([
                                'email' => $email,
                                'token' => $token,
                                'classe_id' => $request->input('class_id')
                            ]);
                            $registrationLink = env('PROJECT_FRONTEND').'/register?invitation_token=' . $token . '&class_id=' . $request->input('class_id');
                            // Send invitation email with the token link
                            Mail::to($email)->queue(new InvitationEmail($invitation, $registrationLink));

                            $invitationsSent[] = $email;
                        }
                    }
                }

                return ResponseHelper::success([
                    'invitations_sent' => $invitationsSent,
                ]);
        }
    }

}
