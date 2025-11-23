<?php

namespace Modules\Lms\Transformers;

use Illuminate\Http\Request;
use Modules\Lms\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lms\Http\Resources\CategoryResource;

class ExternalCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
{
    return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'online' => $this->online,
            'city' => $this->city_id,
            'price' => $this->price,
            'objective' => $this->objective,
            'duration' => $this->duration,
            'category' => new CategoryResource(Category::find($this->category_id)),
            'days_content' => $this->days_content,

        ];
    }

}
