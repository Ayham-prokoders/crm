<?php

namespace Modules\DealManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $actualCourse = $this->classe?->actualCourse;
        $actualSchedule = $this->classe?->actualSchedule;
        // return parent::toArray($request);
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'invoices_count' => $this->when(isset($this->invoices_count), $this->invoices_count),
             'course'         => $actualCourse ? [
                'id'       => $actualCourse->id,
                'name'     => $actualCourse->name,
                'duration' => $actualCourse->duration,
            ] : null,

            'category'       => $actualCourse && $actualCourse->category ? [
                'id'    => $actualCourse->category->id,
                'title' => $actualCourse->category->title,
                'type'  => $actualCourse->category->type
            ] : null,

            'course_type'       => $actualSchedule?->online,
            'class_course_type' => $this->course_type,
            'city'              => $actualSchedule?->city?->name,
            'city_id'           => $actualSchedule?->city_id,
            'classe'         => [
                'id'    => $this->classe?->id,
                'title'  => $this->classe?->title,
            ],
            'schedule' => $actualSchedule ? [
                'id'         => $actualSchedule->id,
                'start_date' => $actualSchedule->start_date,
                'price'      => $actualSchedule->price,
            ] : null,
            'company'   => $this->company ? $this->company: null,
            'user'           => $this->user ? [
                'id'   => $this->user->id,
                'name' => $this->user->name,
                'email' =>$this->user->email
            ] : null,
            'trainees'       => $this->users->map(function ($user) {
                return [
                    'id'   => $user->id,
                    'name' => $user->name,
                    'email' =>$user->email
                ];
            }),
            'type'           => $this->type,
            // 'trainees' => collect([$this->user])
            //     ->merge($this->users)
            //     ->filter()
            //     ->map(function ($user) {
            //         return [
            //             'id'    => $user->id,
            //             'name'  => $user->name,
            //             'email' => $user->email,
            //         ];
            //     }),

            'trainees_count' => collect([$this->user])
                ->merge($this->users)
                ->filter()
                ->count(),

            'price'          => $this->price,
            'currency'       => $this->currency,
            'language'       => $this->language,
            'payment_method' => $this->payment_method,
            'payment_mode'   => $this->payment_mode,
            'created_at'     => $this->created_at->toDateTimeString(),
            'updated_at'     => $this->updated_at->toDateTimeString(),
        ];
    }
}
