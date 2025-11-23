<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Modules\Lms\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    use HasFactory;
    use SyncsWithLMS;

    protected $fillable=[
        'id',
        'project_source',
        'external_id',
        'name',
        'description',
        'slug'
    ];

    /**
     * Get the locations for the city.
     */
    public function locations()
    {
        return $this->hasMany(Location::class);
    }
    protected static function booted(): void
    {
        static::addGlobalScope('project_source_l1', function (Builder $builder) {
            $builder->where('project_source', 'L1');
        });
    }

    /**
     * Scope a query to filter cities by project_source.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $source
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProjectSource(Builder $query, $source)
    {
        if (is_array($source)) {
            return $query->whereIn('project_source', $source);
        }
        return $query->where('project_source', $source);
    }
}
