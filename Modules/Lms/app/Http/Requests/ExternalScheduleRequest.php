<?php

namespace Modules\Lms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalScheduleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'city_id' => 'required|exists:cities,id',
            'online' => 'boolean',
            'course_id' => 'required|exists:external_courses,id',
            'price' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
