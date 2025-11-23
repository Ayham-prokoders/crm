<?php

namespace Modules\DealManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'deal'         => new DealResource($this->deal),
            'bank'      => $this->bank,
            'invoice_type' => $this->invoice_type,
            'invoice_number' => $this->invoice_number,
            'custom_code'   => $this->custom_code,
            'final_price'   => $this->final_price,
            'tax'            => $this->tax,
            'discount'      => $this->discount,
            'is_percentage'      => $this->is_percentage,
            'duration'     => $this->duration,
            'created_at'   => $this->created_at,
        ];
    }
}
