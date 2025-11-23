<?php

namespace Modules\TrainerManagement\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class TrainerAttendanceRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // 'status' => ['nullable', new EnumValue(AttendanceStatusEnum::class)],
            // 'note' => ['nullable', 'string'],
            'signature' =>['required','string'],
            'class_id' =>['required','exists:classes,id'],
            'session_id'=>['required','exists:session_courses,id']
        ];
    }
}
