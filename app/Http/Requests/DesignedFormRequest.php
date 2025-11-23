<?php

namespace App\Http\Requests;

use App\Models\DesignedForm;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\FormEnum;
use BenSampo\Enum\Rules\EnumValue;
class DesignedFormRequest extends FormRequest
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
            // 'slug' => 'required|string|unique:designed_forms,slug',
            'title' => 'required|string',
            'description'=>'nullable|string',
            'type' => ['required', new EnumValue(FormEnum::class)],
            'classe_id'=>'required_if:type,pre_course',
            'image'=>'string|nullable'
            // 'recipients'=>'nullable'
        ];
    }
}
