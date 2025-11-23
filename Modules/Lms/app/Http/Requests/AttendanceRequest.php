<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Lms\Enums\AttendanceStatusEnum;

class AttendanceRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new EnumValue(AttendanceStatusEnum::class)],
            'note' => ['nullable', 'string'],
        ];
    }

}
