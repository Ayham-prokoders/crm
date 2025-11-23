<?php

namespace Modules\DealManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\DealManagement\Enums\DealTypeEnum;
use BenSampo\Enum\Rules\EnumValue;
use Modules\DealManagement\Enums\PaymentModeEnum;
use Illuminate\Validation\Rule;

class DealRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $typeCourse = $this->input('course_type', 'official');

        return [
            'course_id' => [
                'required',
                Rule::exists($typeCourse === 'custom' ? 'external_courses' : 'courses', 'id')
            ],
            'course_type'     => ['required', 'string'],
            'classe_id'       => ['required', 'exists:classes,id'],
            'type'            => ['required', new EnumValue(DealTypeEnum::class)],
            'company_id'      => ['nullable', 'required_if:type,' . DealTypeEnum::company, 'exists:companies,id'],
            'user_id'         => ['nullable', 'required_if:type,' . DealTypeEnum::individual, 'exists:users,id'],
            'price'           => ['required'],
            'currency'        => ['required'],
            'language'        => ['required'],
            'payment_method'  => ['required', 'string'],
            'payment_mode'    => ['required', new EnumValue(PaymentModeEnum::class)],
            'trainees'        => ['nullable', 'array'],
            'trainees.*'      => ['exists:users,id'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
