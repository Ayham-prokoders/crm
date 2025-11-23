<?php

namespace Modules\Lms\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Http\Requests\SessionRequest;
use Modules\Lms\Models\{Classe,SessionCourse};
use Modules\Lms\Http\Resources\SessionResource;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        // if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('supervisor'))) {
        //     return ResponseHelper::authorizationFail('Unauthorized action');
        // }

        $query =  QueryBuilder::for(SessionCourse::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['name', 'created_at']);


            $sessions = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'sessions' => SessionResource::collection($sessions),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $sessions->total(),
                'per_page' => $sessions->perPage(),
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'from' => $sessions->firstItem(),
                'to' => $sessions->lastItem(),
                'links' => [
                    'first' => $sessions->url(1),
                    'last' => $sessions->url($sessions->lastPage()),
                    'prev' => $sessions->previousPageUrl(),
                    'next' => $sessions->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }
     public function list(Request $request)
    {
        $classId = $request->input('class_id');

        if (!$classId) {
            return ResponseHelper::invalidData();
        }
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        // Initialize QueryBuilder for sessions
        $query = QueryBuilder::for(SessionCourse::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['name', 'created_at'])
            ->where('classe_id', $classId);

        if (in_array($role->name, ['admin', 'supervisor'])) {
            // Admins and supervisors can retrieve sessions for the specified class
            $sessions = $query->paginate($request->input('limit'));
        } elseif ($role->name=='trainer') {
            // Check if the user is a trainer for the specified class
            $isTrainer = Auth::user()->trainerClasses()->where('classes.id', $classId)->exists();
            if (!$isTrainer) {
                return ResponseHelper::authorizationFail();
            }
            // Retrieve all sessions for the specified class
            $sessions = $query->paginate($request->input('limit'));
        } elseif ($role->name=='trainee') {
            // Check if the user is a trainee for the specified class
            $isTrainee = Auth::user()->trainees()->where('classes.id', $classId)->exists();
            if (!$isTrainee) {
                return ResponseHelper::authorizationFail();
            }
            // Retrieve all sessions for the specified class
            $sessions = $query->paginate($request->input('limit'));
        }
        elseif ($role->name=='companySupervisor') {
            $userCompany=User::where('company_id',$user->company_id)->get()->pluck('id');
            // Retrieve classes that have trainees belonging to company
            $classesUserCompany = Classe::whereHas('trainees', function ($query) use ($userCompany) {
                $query->whereIn('users.id', $userCompany);
            })->pluck('id');
                // return $classesUserCompany;
            // Retrieve all sessions for the specified class
            $sessions = $query->whereIn('classe_id', $classesUserCompany)
                        ->paginate($request->input('limit'));
        }

        else {
            return ResponseHelper::authorizationFail();
        }

        $data = [
            'sessions' => SessionResource::collection($sessions),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $sessions->total(),
                'per_page' => $sessions->perPage(),
                'current_page' => $sessions->currentPage(),
                'last_page' => $sessions->lastPage(),
                'from' => $sessions->firstItem(),
                'to' => $sessions->lastItem(),
                'links' => [
                    'first' => $sessions->url(1),
                    'last' => $sessions->url($sessions->lastPage()),
                    'prev' => $sessions->previousPageUrl(),
                    'next' => $sessions->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }

    public function store(SessionRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }

        $session = SessionCourse::create([
            'title'=>$request->input('title'),
            'description'=>$request->input('description'),
            'status'=>$request->input('status'),
            'duration'=>$request->input('duration'),
            'startDate'=>$request->input('startDate'),
            'classe_id'=>$request->input('class_id')
        ]);

        return ResponseHelper::create(new SessionResource($session));
    }

    public function show(SessionCourse $session)
    {
        // if (!(Auth::user()->hasRole('admin') || Auth::user()->hasRole('supervisor'))){
        //     return ResponseHelper::authorizationFail();
        // }

        $session->load('classe','attendances');
        return ResponseHelper::success(new SessionResource($session));
    }

    public function update(SessionRequest $request, SessionCourse $session)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }

        $session->update([
            'title'=>$request->input('title'),
            'description'=>$request->input('description'),
            'status'=>$request->input('status'),
            'duration'=>$request->input('duration'),
            'startDate'=>$request->input('startDate'),
            'classe_id'=>$request->input('class_id')
        ]);

        // if ($request->has('classe_id')) {
        //     $class= Classe::find($request->input('classe_id'));
        //     $session->classe()->associate($class);
        // }
        return ResponseHelper::success(new SessionResource($session));
    }


    public function destroy(SessionCourse $session)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }

        $session->delete();
        return ResponseHelper::success();
    }

    public function getAllSession(Request $request){
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

    //    if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
    //        return ResponseHelper::authorizationFail();
    //    }
    $sessions = [];

    if ($role->name=='trainer') {
        // If the user is a trainer, retrieve all sessions where they are the trainer
        $sessions = SessionCourse::whereHas('classe', function ($query) use ($user) {
            $query->where('trainer_id', $user->id);
        })->get();
    } elseif ($role->name=='trainee') {
        // If the user is a trainee, retrieve all sessions where they are enrolled
        $sessions = SessionCourse::whereHas('classe.trainees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
    }
    elseif(in_array($role->name, ['admin', 'supervisor'])){
        $sessions=SessionCourse::all();
    }

    return ResponseHelper::success(SessionResource::collection($sessions));
    }


    public function getTrainerSession(Request $request)
    {
        $classId = $request->class_id;
        $trainerId = $request->trainer_id;

        $sessions = SessionCourse::where('classe_id', $classId)
            ->with([
                'trainerAttendances' => function ($query) use ($trainerId) {
                    $query->where('trainer_id', $trainerId)
                        ->select('id', 'session_id', 'trainer_id', 'signature');
                }
            ])
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title,
                    'description' => $session->description,
                    'status' => $session->status,
                    'duration' => $session->duration,
                    'startDate' => $session->startDate,
                    'classe_id' => $session->classe_id,
                    'attendances' => $session->trainerAttendances,
                ];
            });

        return ResponseHelper::success($sessions);
    }


    public function getTraineeSession(Request $request)
    {
        $classId = $request->class_id;
        $traineeId = $request->trainee_id;

        $sessions = SessionCourse::where('classe_id', $classId)
            ->with([
                'attendances' => function ($query) use ($traineeId) {
                    $query->where('trainee_id', $traineeId)->select('id', 'session_id', 'trainee_id', 'signature');
                }
            ])
            ->get();
        return ResponseHelper::success($sessions);
        // return ResponseHelper::success(SessionResource::collection($sessions));
    }

}
