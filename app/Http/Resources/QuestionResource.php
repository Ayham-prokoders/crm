<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'type' => $this->type,
            'question' => $this->question,
            'options' => $this->options,
            'is_required'=>$this->is_required,
           // Load the answers from regular users
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
           // Load the answers from guest surveys
        //    'guestSurveyAnswers' => GuestSurveyAnswerResource::collection($this->whenLoaded('guestSurveyAnswers'))

        ];
    }
}
