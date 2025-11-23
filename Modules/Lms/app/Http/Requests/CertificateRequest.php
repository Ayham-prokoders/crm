<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    use FormRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'class_id'=>'required',
            // 'ID_certificate'=>'required',
            'image'=>'required',
            'type' => 'required|string',
            'course_type' => 'required|string',
            'pdf'=>'required',
            'user_id'=>'required',
            'show_in_website'=>'nullable',
        ];
    }
}
