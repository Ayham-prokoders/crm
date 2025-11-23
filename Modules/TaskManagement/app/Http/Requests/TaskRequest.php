<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'deal_id' => ['required','exists:deals,id'],
            'assign_to' => ['nullable','exists:users,id'],
            'status' => ['required','string'],
            'cancellation_reason' => ['nullable', 'string'],
            'description' => ['nullable','string'],
            // 'title' => ['required','string'],
            'duration' => ['nullable','string'],

            'hotel_booking' => ['nullable','boolean'],
            'hotel_name' =>['nullable','string'],
            'hotel_fees' => ['nullable','string'],
            'hotel_paid' => ['nullable','boolean'],
            'taxi_booking1' => ['nullable','boolean'],
            'taxi_booking2' => ['nullable','boolean'],
            'pre_questioner' => ['nullable','boolean'],


            'hotel_note' => ['nullable','string'],
            'notes' =>['nullable','string'],
            'evaluation_by_email' => ['nullable','boolean'],
            'hotel_paid_2' => ['nullable','boolean'],
            'flight_ticket' => ['nullable','boolean'],
            'joining_email' => ['nullable','boolean'],
            'location_sent' => ['nullable','boolean'],
            'paid' => ['nullable','boolean'],
            'payment_date' => ['nullable','date'],

            'task_for' => ['nullable','array'],
            'task_for.*' => ['exists:users,id'],

            'requirements'=> ['nullable','string'],
            'sales_person'=> ['nullable','string'],

            'tutor_rating' =>['nullable','numeric'],
            'course_confirm' =>['nullable','string'],
            'confirm_tutor' =>['nullable','string'],
            'priority' =>['nullable','string'],
            'tutor_confirmation' =>['nullable','string'],
            'tutor' =>['nullable','string'],
            'zoom_link' => ['nullable','boolean'],
            'tutor_report_received' => ['nullable','boolean'],
            'trainer_agreement' => ['nullable','boolean'],
            'is_new' => ['nullable','boolean'],
            'contact_id' => ['nullable', 'array'],
            'contact_id.*' => ['exists:contact_directories,id'],

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
