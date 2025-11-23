<?php

namespace Modules\Lms\Models;

use App\Traits\SyncsWithLMS;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SyncsWithLMS;

    protected $fillable=[
        'id',
        'code',
        'project_source',
        'lang_code',
        'title',
        'description',
        'type',
        'link_id',
        'external_id'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('project_source_l1', function (Builder $builder) {
            $builder->where('project_source', 'L1');
        });
    }

    /**
     * Scope a query to filter categories by project_source.
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
