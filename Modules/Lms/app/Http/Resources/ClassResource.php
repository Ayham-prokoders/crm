<?php

namespace Modules\Lms\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Modules\Lms\Models\{Course,Schedule};
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Transformers\ExternalCourseResource;
use Modules\Lms\Transformers\ExternalScheduleResource;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);
        return [
            // 'lang_code'=> $this->lang_code,
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'course_type' => $this->course_type,
            'start_date' => $this->startDate,
            'content_days'=>$this->content_days,
            'trainee_count' => $this->trainees->count(),
            // 'trainees' => UserResource::collection($this->trainees),
            'trainees' => UserResource::collection($this->whenLoaded('trainees')),


            'trainer' =>new UserResource(User::find($this->trainer_id)),
            'course' => $this->actual_course
                ? ($this->course_type === 'custom'
                    ? new ExternalCourseResource($this->actual_course)
                    : new CourseResource($this->actual_course))
                : null,

            'schedule' => $this->actual_schedule
            ? ($this->course_type === 'custom'
                ? new ExternalScheduleResource($this->actual_schedule)
                : new ScheduleResource($this->actual_schedule))
            : null,


            // 'course'=> new CourseResource(Course::find($this->course_id)),
            // 'schedule'=>new ScheduleResource(Schedule::find($this->schedule_id)),
            // 'sessions' => SessionResource::collection($this->sessions),
            'attendances'=> AttendanceResource::collection($this->attendances),
            // 'feedbacks' => FeedbackResource::collection($this->feedbacks),
            // 'content'=>ContentResource::collection($this->content)

        ];
    }
}
