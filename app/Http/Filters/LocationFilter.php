<?php

namespace App\Http\Filters;

use Modules\Lms\Models\City;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class LocationFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $locationName = City::find($value)->name ?? null;

            if ($locationName) {
                $query->where('location', $locationName);
            }
    }
}
