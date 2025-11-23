<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Lms\Enums\FeedbackEnum;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'message'=>'nullable|string',
            'rate'=>'nullable',
            'trainee_id' => 'nullable',
            'course_id' => 'nullable',
            'trainer_id' => 'nullable',
            'trainers_rate' => 'nullable',
            'materials_rate' => 'nullable',
            'hospitality_rate' => 'nullable',
            'hotel_rate' => 'nullable',
            'type' => ['required', new EnumValue(FeedbackEnum::class)],
        ];
    }
    public function authorize()
    {
        return true;
    }
}
