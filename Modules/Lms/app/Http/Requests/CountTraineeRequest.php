<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CountTraineeRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'class_id'=>'required',
        ];
    }
}
