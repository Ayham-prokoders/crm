<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\{User,GuestSurvey};

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $guestSurvey = $this->guestSurvey;
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'answer' => $this->answer,
            'user_id' => $this->user_id,
            'guest_survey_id' =>$this->guest_survey_id,
            // 'guestSurvey' => new GuestSurveyResource($guestSurvey),
            'email' => $guestSurvey ? $guestSurvey->email : null,
        ];
    }
}
