<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string',
            'description'=>'nullable|string',
            // 'file'=>'nullable|string'
        ];
    }
}
