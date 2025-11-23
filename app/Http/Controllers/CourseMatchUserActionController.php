<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseMatchUserAction;

class CourseMatchUserActionController extends Controller
{
    /**
     * list of actions
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $logs = CourseMatchUserAction::with(['user', 'sourceCourse', 'targetCourse'])
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('email', 'like', '%' . $request->email . '%');
                });
            })
            ->when($request->filled('action'), function ($query) use ($request) {
                $query->where('action', $request->action);
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $logs->map(function ($log) {
                return [
                    'id'        => $log->id,
                    'user'      => $log->user->email,
                    'action'    => $log->action,
                    'note'      => $log->note,
                    'source_course' => $log->sourceCourse?->name,
                    'target_course' => $log->targetCourse?->name,
                    'created_at'    => $log->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }
}
