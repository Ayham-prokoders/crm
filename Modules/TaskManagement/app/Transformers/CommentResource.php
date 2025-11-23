<?php

namespace Modules\TaskManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'task_id' => $this->task_id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'mentions' => $this->mentions,
            'attachments' => $this->attachments,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
