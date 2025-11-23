<?php

namespace Modules\TaskManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\TaskManagement\Models\ContactDirectory;

class ExternalTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
       return [
            'id' => $this->id,
            'task_code' => $this->task_code,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'description' => $this->description,
            'title' => $this->title,

            'requirements' => $this->requirements,
            'requirement_mentions' => $this->requirement_mentions,
            'sales_person' => $this->sales_person,

            'hotel_booking' => $this->hotel_booking,
            'hotel_name' => $this->hotel_name,
            'hotel_fees' => $this->hotel_fees,
            'hotel_paid' => $this->hotel_paid,

            'taxi_booking1' => $this->taxi_booking1,
            'taxi_booking2' => $this->taxi_booking2,
            'pre_questioner' => $this->pre_questioner,

            'hotel_note' => $this->hotel_note,
            'notes' => $this->notes,
            'evaluation_by_email' => $this->evaluation_by_email,
            'flight_ticket' => $this->flight_ticket,
            'hotel_paid_2' => $this->hotel_paid_2,

            'joining_email' => $this->joining_email,
            'location_sent' => $this->location_sent,
            'paid' => $this->paid,
            'payment_date' => $this->payment_date,

            'is_new' => $this->is_new,
            'tutor_rating' => $this->tutor_rating,
            'course_confirm' => $this->course_confirm,
            'confirm_tutor' => $this->confirm_tutor,
            'priority' => $this->priority,
            'tutor_confirmation' => $this->tutor_confirmation,
            'tutor' => $this->tutor,
            'zoom_link' => $this->zoom_link,
            'tutor_report_received' => $this->tutor_report_received,
            'trainer_agreement' => $this->trainer_agreement,

            'company_name' => $this->company_name,
            'course_name' => $this->course_name,
            'city' => $this->city,
            'price' => $this->price,
            'course_date' => $this->course_date,
            'course_duration' => $this->course_duration,
            'date' => $this->date,

            'contact' => ContactDirectory::whereIn('id', $this->contact ?? [])->get()->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'role' => $contact->role,
                    'email' => $contact->email,
                ];
            }),

            'trainees' => $this->trainees,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
