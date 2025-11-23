<?php

namespace Modules\Lms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalCourseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'online' => 'required',
            'name' => 'required|string|max:255',
            'city_id' => 'nullable|integer',
            'price' => 'nullable|string|max:255',
            'objective' => 'nullable|string',
            'days_content' => 'nullable|array',
            'days_content.*.path' => 'string',
            'duration' => 'nullable|string|max:255',
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
