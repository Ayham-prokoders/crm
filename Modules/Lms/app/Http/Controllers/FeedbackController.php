<?php

namespace Modules\Lms\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Lms\Models\Feedback;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lms\Http\Requests\FeedbackRequest;
use Modules\Lms\Http\Resources\FeedbackResource;

class FeedbackController extends Controller
{

    /**
     * Store a newly created feedback.
     *
     */
    public function store(FeedbackRequest $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if ($role->name == 'trainee' && $user->trainees()->count() === 0) {
            return ResponseHelper::authorizationFail();
        }
        $feedback = Feedback::create([
            'type' => $request->input('type'),
            'message' => $request->input('message'),
            'rate' => $request->input('rate'),
            'trainee_id' => Auth::id(),
            'trainer_id'=>$request->input('trainer_id'),
            'course_id'=>$request->input('course_id')
        ]);

        return ResponseHelper::create(new FeedbackResource($feedback));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        // Allow trainees to view their own feedback
        if ($role->name=='trainee') {
            $feedback = Feedback::where('trainee_id', $user->id)
                ->get();
        }
        elseif ($role->name == 'trainer') {
            // Trainers can only view feedback about themselves if type is trainers
            $feedback = Feedback::where('type', '!=', 'trainers')
            ->orWhere(function ($query) use ($user) {
                $query->where('type', 'trainers')
                    ->where('trainer_id', $user->id);
            })
            ->get();
            // return $feedback;
        }
        else {
            // Only admins, supervisors can view all feedback
            if (!in_array($role->name, ['admin', 'supervisor'])) {
                return ResponseHelper::authorizationFail();
            }

            $feedback = Feedback::all();
        }

        return ResponseHelper::success(FeedbackResource::collection($feedback));
    }


    public function destroy(Feedback $feedback)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }

        $feedback->delete();
        return ResponseHelper::success();
    }
}
