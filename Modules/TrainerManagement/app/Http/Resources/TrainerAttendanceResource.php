<?php

namespace Modules\TrainerManagement\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerAttendanceResource extends JsonResource
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
            'session'=>$this->session,
            'signature'=>$this->signature,
            'class'=>$this->classe,
            'status' => $this->status,
            // 'note' => $this->note,
            'trainer' => new UserResource($this->trainer),
        ];
    }
}
