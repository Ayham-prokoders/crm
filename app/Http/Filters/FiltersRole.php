<?php

namespace App\Http\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersRole implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->whereHas('role', function (Builder $query) use ($value) {
            if (is_numeric($value)) {
                $query->where('id', $value);
            } else {
                $query->where('name', 'like', "%$value%");
            }
        });
    }
}
