<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module' => $this->module,
            'record_id' => $this->record_id,
            'user_id' => $this->user_id,
            'user_name' => optional($this->user)->name,
            'action' => $this->action,
            'changes' => $this->changes,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
