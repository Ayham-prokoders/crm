<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string'],
            'cancellation_reason' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'hotel_booking' => ['nullable', 'boolean'],
            'hotel_name' => ['nullable', 'string'],
            'hotel_fees' => ['nullable', 'string'],
            'hotel_paid' => ['nullable', 'boolean'],

            'taxi_booking1' => ['nullable', 'boolean'],
            'taxi_booking2' => ['nullable', 'boolean'],
            'pre_questioner' => ['nullable', 'boolean'],

            'requirements' => ['nullable', 'string'],
            'requirement_mentions' => ['nullable', 'array'],
            'requirement_mentions.*' => ['string'],

            'hotel_note' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],

            'evaluation_by_email' => ['nullable', 'boolean'],
            'flight_ticket' => ['nullable', 'boolean'],
            'hotel_paid_2' => ['nullable', 'boolean'],

            'sales_person' => ['nullable', 'string'],
            'joining_email' => ['nullable', 'boolean'],
            'location_sent' => ['nullable', 'boolean'],
            'paid' => ['nullable', 'boolean'],
            'payment_date' => ['nullable', 'date'],

            'zoom_link' => ['nullable', 'boolean'],
            'tutor_report_received' => ['nullable', 'boolean'],
            'trainer_agreement' => ['nullable', 'boolean'],

            'tutor_rating' => ['nullable', 'numeric'],
            'course_confirm' => ['nullable', 'string'],
            'confirm_tutor' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'tutor_confirmation' => ['nullable', 'string'],
            'tutor' => ['nullable', 'string'],

            'is_new' => ['nullable', 'boolean'],
            'contact' => ['nullable', 'array'],
            'contact.*' => ['exists:contact_directories,id'],

            'trainees' => ['nullable', 'string'],

            'company_name' => ['nullable', 'string'],
            'course_name' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric'],
            'course_date' => ['nullable', 'date'],
            'course_duration' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
