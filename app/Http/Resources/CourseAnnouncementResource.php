<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Modules\Lms\Http\Resources\{ClassResource ,CourseResource};
use Illuminate\Http\Resources\Json\JsonResource;

class CourseAnnouncementResource extends JsonResource
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
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'class' => new ClassResource($this->classe),
            'course' =>new CourseResource($this->course),
            'image' => $this->image,
            'available' => $this->available,
            'attachments' => $this->attachments,
            'read'=>$this->read,
            'created_at'=>$this->created_at
        ];
    }
}
