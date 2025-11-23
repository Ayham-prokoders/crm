<?php

namespace Modules\Lms\Http\Requests;

use Illuminate\Validation\Rule;
use App\Traits\FormRequestTrait;
use App\Http\Helper\ResponseHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClassRequest extends FormRequest
{
    use FormRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // 'lang_code'=>'required|string',
            // 'slug'=>'required|string',
            'title' => [
                'required',
                'string',
                Rule::unique('classes', 'title')->ignore($this->route('classe')),
            ],
            'course_type'=>'required|string',
            'description'=>'nullable|string',
            // 'type'=>['required', new EnumValue(ClassTypeEnum::class)],
            // 'status' => ['nullable', new EnumValue(ClassStatusEnum::class)],
            // 'startDate'=>'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'This title has already been taken — please choose another.',
            'title.required' => 'The class title is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ResponseHelper::invalidData(
                $validator->errors()->first(),
            )
        );
    }
}
