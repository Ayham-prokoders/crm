<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ShowInWebsiteRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'certificates' => 'required|array',
            'certificates.*' => 'required|exists:certificates,id',
        ];
    }
}
