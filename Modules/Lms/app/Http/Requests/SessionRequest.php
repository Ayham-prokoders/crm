<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Lms\Enums\SessionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // 'lang_code'=>'required|string',
            'title'=>'nullable|string',
            'description'=>'nullable|string',
            'duration'=>'nullable|integer',
            'status'=>['nullable',new EnumValue(SessionStatusEnum::class)],
            'startDate'=>'nullable',
            'class_id'=>'nullable'
        ];
    }
}
