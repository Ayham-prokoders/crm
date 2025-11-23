<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Modules\Lms\Models\{Course,Classe,ExternalCourse};
use App\Models\{User};
use Modules\Lms\Http\Resources\ClassResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Transformers\ExternalCourseResource;

class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return[
            'id'=>$this->id,
            'course_custom_name'=>$this->course_custom_name,
            'course_type'=>$this->course_type,
            'first_name'=>$this->first_name,
            'middle_name'=>$this->middle_name,
            'last_name'=>$this->last_name,
            'ID_certificate'=>$this->ID_certificate,
            'image'=>$this->image,
            'pdf'=>$this->pdf,
            'origin'=>$this->origin,
            'show_in_website'=>$this->show_in_website,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'type' => $this->type,
            'course_id' => $this->course_type === 'custom'
                ? new ExternalCourseResource(ExternalCourse::find($this->course_id))
                : new CourseResource(Course::find($this->course_id)),
            'user_id'=>User::find($this->user_id)?new UserResource(User::find($this->user_id)):$this->user_id,
            // 'course_id'=>Course::find($this->course_id)?new CourseResource(Course::find($this->course_id)):$this->course_id,
            'class'=>User::find($this->user_id)&&User::find($this->user_id)->trainees()->where('course_id', $this->course_id)?new ClassResource(User::find($this->user_id)->trainees()->where('course_id', $this->course_id)->first()):null
            ];
    }
}
