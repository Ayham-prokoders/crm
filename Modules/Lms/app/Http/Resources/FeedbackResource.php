<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Modules\Lms\Http\Resources\CourseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
        'id'=>$this->id,
        'type'=>$this->type,
        'message'=>$this->message,
        'rate'=>$this->rate,
        'is_new'=>$this->is_new,
        'trainers_rate'=>$this->trainers_rate,
        'materials_rate'=>$this->materials_rate,
        'hospitality_rate'=>$this->hospitality_rate,
        'hotel_rate'=>$this->hotel_rate,
        'created_at'=>$this->created_at?$this->created_at->format('Y-m-d H:i:s'):null,
        // 'classe_id'=>new ClassResource($this->classe),
        'trainee_id'=>new UserResource($this->trainee),
        'trainer_id'=>new UserResource($this->trainer),
        'course_id'=>new CourseResource($this->course),


        ];
    }
}
