<?php

namespace Modules\Lms\Http\Requests;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'bill_email' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
        ];
    }


}
