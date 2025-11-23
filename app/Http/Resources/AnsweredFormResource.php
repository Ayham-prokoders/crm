<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\{User,DesignedForm};

class AnsweredFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'form' => new DesignedFormResource(DesignedForm::find($this->designed_form_id)),
            'user' =>new UserResource(User::find($this->user_id)),
            'rate'=> $this->rate,
            'message'=> $this->message,
            'answered' => $this->answered,

        ];
    }
}
