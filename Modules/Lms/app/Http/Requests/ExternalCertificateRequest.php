<?php

namespace Modules\Lms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'project_source' => 'nullable|string',
            'type' => 'required|string',
            'category' => 'required|integer',
            'course' => 'required|string',
            'city' => 'nullable|string',
            'schedule' => 'nullable|date',
            'class' => 'nullable|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'show_in_website' => 'boolean',
            'image' => 'nullable|string',
            'course_type' => 'required|string',
            'ID_certificate' => 'nullable|string|unique:external_certificates,ID_certificate',
            'pdf' => 'nullable|string',
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
