<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTagrtCourseRequest extends FormRequest
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
            'site_key' => 'required|string|in:L1,L0,lpc_en,lpc_ar,R1,R0,M1,M0',
            'type' => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
