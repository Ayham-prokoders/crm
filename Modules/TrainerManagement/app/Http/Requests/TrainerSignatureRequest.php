<?php

namespace Modules\TrainerManagement\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class TrainerSignatureRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'class_id' => ['required','exists:classes,id'],
            'trainee_id' => ['required','exists:users,id'],
            'signature' => ['required','string'],
        ];
    }
}
