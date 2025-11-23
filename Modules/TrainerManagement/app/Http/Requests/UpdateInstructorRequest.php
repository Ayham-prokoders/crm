<?php

namespace Modules\TrainerManagement\Http\Requests;

use App\Models\User;
use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $user = User::find($this->instructor->user_id);
        return [
            // user data
            'name' => 'required|string|max:255',
            'middel_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|string',

            //instructor data
            'location' => 'nullable',
            // 'slug' => 'required|string|unique:instructors,slug,'.$this->instructor->id,
            'rate' => 'nullable',
            'field' => 'nullable|string|max:255',
            'work_history' => 'nullable|string',
            'category_id' => 'nullable',

            'professional_summary' => 'nullable|string',

            'experience' => 'nullable|array',
            'experience.*.job_title' => 'nullable|string|max:255',
            'experience.*.organization' => 'nullable|string|max:255',
            'experience.*.start_date' => 'nullable|date',
            'experience.*.responsibilities' => 'nullable|array',
            'experience.*.responsibilities.*' => 'nullable|string',

            'qualification' => 'nullable|array',
            'qualification.*.degree' => 'nullable|string|max:255',
            'qualification.*.institution' => 'nullable|string|max:255',
            'qualification.*.graduation_date' => 'nullable|date',
            'qualification.*.major' => 'nullable|string',

            'certification' => 'nullable|array',
            'certification.*.title' => 'nullable|string|max:255',
            'certification.*.organization' => 'nullable|string',
            'certification.*.date' => 'nullable|string',
            'certification.*.validity' => 'nullable|date',
            'certification.*.file' => 'nullable|string',


            'course_experience_lpc' => 'nullable|array',
            'course_experience_lpc.*.title' => 'nullable|string|max:255',
            'course_experience_lpc.*.subject' => 'nullable|string',
            'course_experience_lpc.*.audience' => 'nullable|string',
            'course_experience_lpc.*.duration' => 'nullable|string',
            'course_experience_lpc.*.mode_of_delivery' => 'nullable|string',
            'course_experience_lpc.*.geographic_location' => 'nullable',
            'course_experience_lpc.*.number_of_session' => 'nullable|string',

            'specilized_topics' => 'nullable|array',
            // 'specilized_topics.*' => 'nullable',

            'languages' => 'nullable|array',
            'languages.*.title' => 'nullable',
            'languages.*.level' => 'nullable|string',

            'awards' => 'nullable|array',
            'awards.*.title' => 'nullable|string',
            'awards.*.body' => 'nullable|string',
            'awards.*.description' => 'nullable|string',
            'awards.*.date' => 'nullable|date',
            'awards.*.file' => 'nullable|string',


            'testimonials' => 'nullable|array',
            'testimonials.*.name' => 'nullable|string',
            'testimonials.*.position' => 'nullable|string',
            'testimonials.*.organization' => 'nullable|string',
            'testimonials.*.contact_info' => 'nullable|string',
            'testimonials.*.content' => 'nullable|string',
            'testimonials.*.video_link' => 'nullable|string',

            'social_media_links' => 'nullable|array',
            'social_media_links.*.title' => 'nullable|string',
            'social_media_links.*.link' => 'nullable|string',

            'portofolio_url' => 'nullable|string',
            'linkedln_url' => 'nullable|string',

            'training_modes' => 'nullable',
            'availability' => 'nullable',
            'country_availability' => 'nullable',


            'engagement' => 'nullable|array',
            'engagement.*.name' => 'nullable|string',
            'engagement.*.date' => 'nullable|date',
            'engagement.*.topic' => 'nullable|string',

            'publication' => 'nullable|array',
            'publication.*.title' => 'nullable|string',
            'publication.*.date' => 'nullable|date',
            'publication.*.publisher' => 'nullable|string',
            'publication.*.link' => 'nullable|string',

            'facebook'=> 'nullable|string',
            'instagram'=> 'nullable|string',
            'twitter'=> 'nullable|string',
            'whatsapp'=> 'nullable|string',
        ];
    }
}
