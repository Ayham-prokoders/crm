<?php

namespace Modules\Lms\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Models\{Category};

class CourseResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'online' => $this->online,

             'deals' => $this->deals->map(function ($deal) {
                return [
                    'name' => $deal->name,
                    'date' => optional($deal->created_at)?->format('Y-m-d'),
                    'cities' => optional($deal->classe)?->schedules?->pluck('city.name')->unique()->values(),
                ];
            }),

            // 'duration' => $this['duration'],
            // 'startDate'=>$this['startDate'],
            // 'city'=>$this['city'],
            // 'days_content' => json_decode($this['days_content'], true),
            // 'related_courses' => json_decode($this['related_courses'], true),
            'category_id' => new CategoryResource(Category::find($this->category_id)),

        ];
    }
}
