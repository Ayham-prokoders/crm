<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use App\Models\TrainerSignature;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'session'=>$this->session_id,
            'signature'=>$this->signature,
            'class'=>$this->classe_id,
            'status' => $this->status,
            'note' => $this->note,
            'trainee' => new UserResource($this->trainee),
            // 'trainer_signature' => TrainerSignature::where('classe_id', $this->classe_id)
            //     ->where('trainee_id', $this->trainee_id)
            //     ->value('signature'),
            ];
    }
}
