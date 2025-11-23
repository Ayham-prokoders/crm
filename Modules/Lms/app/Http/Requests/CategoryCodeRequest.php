<?php

namespace Modules\Lms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_code' => 'required|string',
            'category_id' => 'required',
            'project_source'=>'required|string'
        ];
    }

}
