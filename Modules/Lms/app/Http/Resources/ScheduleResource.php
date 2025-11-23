<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
        'id'=>$this->id,
        'start_date'=>$this->start_date,
        // 'city_id'=>$this->city_id,
        'city'=>$this->city,
        'online'=>$this->online,
        'price'=>$this->price,
        'trainer_id'=>new UserResource($this->trainer),
        'course_id'=>new CourseResource($this->course),
    ];
    }
}
