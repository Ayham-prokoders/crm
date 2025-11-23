<?php

namespace Modules\TrainerManagement\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
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
            // 'uuid' => $this->uuid,
            'slug' => $this->slug,
            'user' => new UserResource($this->whenLoaded('user')),
            'location' => $this->city,
            'rate' => $this->rating,
            // 'field' => $this->field,
            'work_history' => $this->work_history,
            'category_id' => $this->category_id,
            'professional_summary' => $this->professional_summary,
            'experience' => $this->experience,
            'qualification' => $this->qualification,
            'certification' => $this->certification,
            'course_experience_lpc' => $this->course_experience_lpc,
            'specilized_topics' => $this->topics->pluck(['id']),
            'languages' => $this->languages,
            'awards' => $this->awards,
            'testimonials' => $this->testimonials,
            'social_media_links' => $this->social_media_links,
            'portofolio_url' => $this->portofolio_url,
            'linkedln_url' => $this->linkedln_url,
            'training_modes' => $this->training_modes,
            'availability' => $this->availability,
            'country_availability' => $this->country_availability,
            'date_of_submission' => $this->date_of_submission,
            'engagement' => $this->speaking_engagements,
            'publication' => $this->publications,
            'facebook'=>$this->facebook,
            'instagram'=>$this->instagram,
            'twitter'=>$this->twitter,
            'whatsapp'=>$this->whatsapp,
            'certification_file' => $this->getCertificationFiles($this->certification),

            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];


    }

    private function getCertificationFiles($certifications)
    {
        // Decode the JSON string into an array if it's not already an array
        if (is_string($certifications)) {
            $certifications = json_decode($certifications, true);
        }

        // Ensure $certifications is an array before processing
        if (is_array($certifications)) {
            return array_map(function ($certification) {
                // Modify the 'file' field to return the full asset path if it exists
                return [
                    'title' => $certification['title'] ?? null,
                    'organization' => $certification['organization'] ?? null,
                    'date' => $certification['date'] ?? null,
                    'validity' => $certification['validity'] ?? null,
                    'file' => isset($certification['file']) ? asset($certification['file']) : null,
                ];
            }, $certifications);
        }

        return [];
    }


}
