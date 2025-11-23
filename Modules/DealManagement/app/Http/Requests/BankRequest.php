<?php

namespace Modules\DealManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
           'account_holder' => ['required','string'],
            'bank_name'      => ['required','string'],
            'sort_code'      => ['required','string'],
            'swift_bic'      => ['required','string'],
            'iban'           => ['required','string'],
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
