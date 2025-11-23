<?php

namespace Modules\TrainerManagement\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerSignatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // 'trainer_id', 'trainee_id', 'classe_id', 'signature','status'];
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'trainer_id' => $this->trainer,
            'trainee_id' => $this->trainee,
            'classe_id' => $this->classe,
            'signature' => $this->signature,
        ];
    }
}
