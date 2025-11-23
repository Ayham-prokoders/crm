<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseAnnouncementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $typeCourse = $this->input('course_type', 'official');

        return [
            'course_id' => [
                'required',
                Rule::exists($typeCourse === 'custom' ? 'external_courses' : 'courses', 'id')
            ],
            'class_id' => 'required|exists:classes,id',
            'attachments'=>'nullable|array',
            'attachments.*'=>'nullable|string',
            'instructor_ids' => 'required|array',
            'instructor_ids.*' => 'exists:users,id',
            'template' => 'required',
        ];
    }
}



