<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Modules\Lms\Http\Resources\CourseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignedFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'image'=>$this->image,
            'type' => $this->type,
            'description' => $this->description,
            'classe_id' => $this->classe_id,
            'questions' =>QuestionResource::collection($this->questions),
            'recipients' => $this->recipients,
            'roles_id'=>$this->roles,
            'course' => $this->type === 'pre_course' ? new CourseResource($this->classe->course) : null,
            'shareable_link'=>"apps/forms/answer/{$this->slug}"
        ];
    }
}
