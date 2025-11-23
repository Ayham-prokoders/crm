<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            'user_id'=> $this->user_id,
            'user' => new UserResource($this->user),
            'subject'=> $this->subject,
            'message'=> $this->message,
            'level'=> $this->level,
            'type'=> $this->type,
            'role_ids'=>$this->role_ids,
            'recipients'=>$this->recipients
        ];
    }
}
