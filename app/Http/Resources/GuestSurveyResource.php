<?php

namespace App\Http\Resources;

use App\Models\DesignedForm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestSurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        //  'designed_form_id', 'email', 'info'

        return [
            'id' => $this->id,
            'form' => new DesignedFormResource(DesignedForm::find($this->designed_form_id)),
            'email' =>$this->email,
            'info'=>$this->info,
            'user'=>$this->info,
            'answered' => 1,
        ];
    }
}
