<?php

namespace Modules\TaskManagement\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    use FormRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'email' => ['required','email'],
            'role' => ['required','string'],
            // 'class_id'=> ['required','exists:classes,id'],
        ];
    }

}
