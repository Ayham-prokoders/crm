<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetMatchesRequest extends FormRequest
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
        return [
            'project_source' => 'nullable|string|max:20',
            'category_id'    => 'nullable|exists:categories,id',
            'online'         => 'nullable|boolean',
            'course_id'      => 'nullable|exists:courses,id',
            'status'         => 'nullable|in:active,not_active',
        ];
    }
}
