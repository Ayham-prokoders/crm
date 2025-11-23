<?php

namespace Modules\TrainerManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\TrainerManagement\Models\TrainerAttendance;
use Modules\TrainerManagement\Http\Requests\TrainerAttendanceRequest;
use Modules\TrainerManagement\Http\Resources\{TrainerAttendanceResource ,TrainerAttendanceCollection};

class TrainerAttendanceController extends Controller
{
    public function store(TrainerAttendanceRequest $request)
    {
        $trainerId = Auth::id();
        $classId = $request->class_id;
        $sessionId = $request->session_id;

        $attendance = TrainerAttendance::firstOrCreate([
            'session_id' => $sessionId,
            'trainer_id' => $trainerId,
            'classe_id' => $classId,
        ], [
            'status' => 'present',
            'signature' => $request->signature,
        ]);

        return ResponseHelper::success(new TrainerAttendanceResource($attendance));
    }

    public function get(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        if (!$role) {
            return ResponseHelper::authenticationFail();
        }

        if ($role->name === 'trainer') {
            $classIds = $user->trainerClasses()->pluck('id');
            $query = QueryBuilder::for(TrainerAttendance::class)
                ->whereIn('classe_id', $classIds)
                ->where('trainer_id', $user->id)
                ->allowedFilters(['classe_id', 'session_id'])
                ->allowedSorts(['created_at']);
        }
        elseif (in_array($role->name, ['admin', 'supervisor'])) {
            $query = QueryBuilder::for(TrainerAttendance::class)
                ->allowedFilters(['classe_id', 'session_id','trainer_id'])
                ->allowedSorts(['created_at']);
        }
        else {
            return ResponseHelper::authenticationFail();
        }

        if ($request->has('per_page')) {
            $attendances = $query->paginate($request->per_page);
            return ResponseHelper::success(new TrainerAttendanceCollection($attendances));
        }

        $attendances = $query->get();

        return TrainerAttendanceResource::collection($attendances);
    }


}
