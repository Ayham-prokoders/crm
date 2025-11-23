<?php
namespace Modules\DealManagement\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'account_holder'  => $this->account_holder,
            'bank_name'       => $this->bank_name,
            'sort_code'       => $this->sort_code,
            'swift_bic'       => $this->swift_bic,
            'iban'            => $this->iban,
        ];
    }
}
