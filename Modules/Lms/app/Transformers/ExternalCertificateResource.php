<?php

namespace Modules\Lms\Transformers;

use Illuminate\Http\Request;
use Modules\Lms\Http\Resources\CourseResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Http\Resources\CategoryResource;
use Modules\Lms\Models\Category;

class ExternalCertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $category = Category::find($this->category);

        return [
            'id' => $this->id,
            'type' => $this->type,
            // 'category' => new CategoryResource($this->whenLoaded('category')),
            'category' => $category ? new CategoryResource($category) : null,
            'course' => $this->course,
            // 'course' => $this->type === 'custom'
            //     ? new CourseResource($this->course)
            //     : new ExternalCourseResource($this->course),
            'city' => $this->city,
            'schedule' => $this->schedule,
            'class' => $this->class,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'show_in_website' => $this->show_in_website,
            'image' => $this->image,
            'course_type' => $this->course_type,
            'ID_certificate' => $this->ID_certificate,
            'pdf' => $this->pdf,
            'created_at' => $this->created_at,
        ];
    }
}
