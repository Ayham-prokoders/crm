<?php

namespace Modules\Lms\Transformers;

use Illuminate\Http\Request;
use Modules\Lms\Http\Resources\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'city' => new CityResource($this->whenLoaded('city')),
            'online' => $this->online,
            'course' => new ExternalCourseResource($this->whenLoaded('externalCourse')),
            'price' => $this->price,
         
        ];
    }
}
