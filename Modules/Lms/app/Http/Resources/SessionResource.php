<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use Modules\Lms\Http\Resources\ClassResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'id' => $this->id,
            // 'lang_code'=> $this->lang_code,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'duration' => $this->duration,
            'startDate'=>$this->startDate,
            'class'=> new ClassResource($this->classe),
            //'attendance'=> new AttendanceResource($this->attendance),
            // 'classe'=>$this->load('classe')
        ];
    }
}
