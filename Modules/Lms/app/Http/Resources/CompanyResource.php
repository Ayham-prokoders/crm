<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Transformers\ExternalCourseResource;

class CompanyResource extends JsonResource
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
            // 'lang_code'=> $this->lang_code,
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'bill_email' => $this->bill_email,
            'email' => $this->email,
            'phone' => $this->phone,
            'trainees' => collect($this->users)->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'trainee_courses' => $user->trainees->map(function ($class) {
                            $courseResource = $class->course_type === 'custom'
                                ? new ExternalCourseResource(optional($class->externalCourse))
                                : new CourseResource(optional($class->course));

                            return [
                                'title' => $class->title,
                                'course' => $courseResource,
                            ];
                        }),
                    ];
                })->values() ?? [],


            ];
    }
}
