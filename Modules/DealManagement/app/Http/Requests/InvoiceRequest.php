<?php

namespace Modules\DealManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;
use Modules\DealManagement\Enums\InvoiceTypeEnum;

class InvoiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'deal_id'      => ['required', 'exists:deals,id'],
            'bank_id'      => ['required'],
            'invoice_type' => ['required', 'string' , new EnumValue(InvoiceTypeEnum::class)],
            'duration'     => ['nullable'],
            'tax'          => ['nullable'],
            'discount'     => ['nullable'],
            'is_percentage'  => ['nullable'],

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
