<?php

namespace Modules\Lms\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Modules\Lms\Models\ExternalSchedule;
use Modules\Lms\Http\Requests\ExternalScheduleRequest;
use Modules\Lms\Transformers\ExternalScheduleResource;

class ExternalScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ExternalSchedule::with(['externalCourse', 'city', 'trainer']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->input('course_id'));
        }

        $schedules = $query->get();

        return ResponseHelper::success(ExternalScheduleResource::collection($schedules));
    }

    public function store(ExternalScheduleRequest $request)
    {
        $schedule = ExternalSchedule::create($request->validated());

        return ResponseHelper::success(new ExternalScheduleResource($schedule));
    }

    public function show(ExternalSchedule $externalSchedule)
    {
        $externalSchedule->load(['externalCourse', 'city', 'trainer']);

        return ResponseHelper::success(new ExternalScheduleResource($externalSchedule));
    }

    public function update(ExternalScheduleRequest $request, ExternalSchedule $externalSchedule)
    {
        $externalSchedule->update($request->validated());

        return ResponseHelper::success(new ExternalScheduleResource($externalSchedule));
    }

    public function destroy(ExternalSchedule $externalSchedule)
    {
        $externalSchedule->delete();

        return ResponseHelper::success('Deleted successfully');
    }
}
