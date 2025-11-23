<?php

namespace Modules\TrainerManagement\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class SetInstructorRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'instructor_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
        ];
    }

}
