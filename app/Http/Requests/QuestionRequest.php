<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\QuestionEnum;
use BenSampo\Enum\Rules\EnumValue;
class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lang_code'=>'nullable|string',
            'question' => 'required|string',
            'type' => ['required', new EnumValue(QuestionEnum::class)],
            'designed_form_id'=>'nullable',
            'options'=>'nullable'
        ];
    }
}
