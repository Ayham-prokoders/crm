<?php

namespace Modules\Lms\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Lms\Models\{Classe, Attendance};
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lms\Http\Requests\{AttendanceRequest, SignatureRequest};
use Modules\Lms\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
        ]);

        $classId = $request->class_id;
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);


        if (in_array($role->name, ['admin', 'supervisor'])) {
            $attendances = Attendance::where('classe_id', $classId)->get();
        } elseif ($role->name=='trainer') {
            // Check if the user is a trainer for the specified class
            $isTrainer = Auth::user()->trainerClasses()->where('classes.id', $classId)->exists();
            if (!$isTrainer) {
                return ResponseHelper::authorizationFail();
            }
            // Retrieve all attendance records for the specified class
            $attendances = Attendance::where('classe_id', $classId)->get();
        }
        elseif ($role->name=='companySupervisor') {
            // Check if the user is a trainer for the specified class
            $userCompany=User::where('company_id',$user->company_id)->get()->pluck('id');
            // Retrieve all attendance records for the specified class
            $attendances = Attendance::whereIn('trainee_id',$userCompany)
            ->where('classe_id', $classId)->get();
        }

        elseif ($role->name=='trainee') {
            // Retrieve all attendance records for the specified class and user
            $attendances = Attendance::where('classe_id', $classId)
                ->where('trainee_id', Auth::user()->id)
                ->get();
            if ($attendances->isEmpty()) {
                return ResponseHelper::DataNotFound();
            }
        } else {
            return ResponseHelper::authorizationFail();
        }

        // return AttendanceResource::collection($attendances);
        // TrainerSignature::where('classe_id', $classId)
        // ->get()
        // ->keyBy('trainee_id');

        return AttendanceResource::collection($attendances);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        // if (!$role->name=='trainee') {
        //     return ResponseHelper::authorizationFail();
        // }

        $classId = $request->class_id;
        $sessionId = $request->session_id;
        $traineeId=$request->trainee_id;

        $attendance =Attendance::where('classe_id',$classId)
            ->where('session_id',$sessionId)
            ->where('trainee_id',$traineeId)
            ->first();
            if(! $attendance){
                $attendance = Attendance::create([
                    'session_id' => $sessionId,
                    'trainee_id' => $traineeId,
                    'classe_id' => $classId,
                    'status' => $request->status,
                ]);}
            else{

                if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
                    return ResponseHelper::authorizationFail();
                }

                // Update the attendance status and note
                $attendance->update([
                    'status' => $request->status,
                    'note' => $request->note,
                ]);
            }

        return ResponseHelper::success(new AttendanceResource($attendance));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceRequest $request, Attendance $attendance)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }

        // Update the attendance status and note
        $attendance->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return ResponseHelper::success(new AttendanceResource($attendance));
    }

    public function saveSignature(SignatureRequest $request)
    {
        $traineeId = Auth::id();
        $classId = $request->class_id;
        $sessionId = $request->session_id;

        if ($request->filled('signature')) {
            $attendance = Attendance::where('classe_id', $classId)
                ->where('session_id', $sessionId)
                ->where('trainee_id', $traineeId)
                ->first();
            if (!$attendance) {
                $attendance = Attendance::create([
                    'session_id' => $sessionId,
                    'trainee_id' => $traineeId,
                    'classe_id' => $classId,
                    'signature' => $request->signature,
                    'status' => 'present',
                ]);
            } else {
                // Update the existing attendance record
                $attendance->update([
                    'signature' => $request->signature,
                    'status' => 'present',
                ]);
            }
        }

        return ResponseHelper::success(new AttendanceResource($attendance));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    //generate attendance class report
    public function generateAttendancePdf($classId, $userId)
    {
        $class = Classe::with(['course', 'sessions.attendance' => function ($query) use ($userId) {
            $query->where('trainee_id', $userId);
        }])->findOrFail($classId);
        // return($class);
        $user = User::findOrFail($userId);

        $pdf = Pdf::loadView('new.attendance-pdf', [
            'class' => $class,
            'user' => $user
        ]);

        return $pdf->download("Attendance_{$user->name}.pdf");
    }



}
