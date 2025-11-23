<?php

namespace Modules\Lms\Traits;

use App\Models\User;
use App\Models\CourseCodeMatch;
use Modules\DealManagement\Models\Deal;
use Illuminate\Database\Eloquent\Builder;

use Modules\Lms\Models\{Classe ,Category ,Feedback ,Schedule};

trait CourseRelations {
    
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function trainers()
    {
        return $this->hasManyThrough(
            User::class,Classe::class,'course_id','id', 'id','trainer_id'
        );
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function codeMatches()
    {
        return $this->hasMany(CourseCodeMatch::class, 'target_course_id')->with('sourceCourse');;
    }
    public function sourceMatches()
    {
        return $this->hasMany(CourseCodeMatch::class, 'source_course_id')->with('targetCourse');;
    }

    public function allMatches()
    {
        return $this->sourceMatches->merge($this->codeMatches);
    }

    public function scopeFilterByProject(Builder $query, ?string $siteKey)
    {
        if (!$siteKey) return $query;

        $project = config("services.sites.$siteKey.project");
        return $query->where('project_source', $project);
    }

    public function getCurrentMatchedCodeAttribute()
    {
        return $this->codeMatches()
            ->where('status', 'active')
            ->latest()
            ->first()?->applied_code ?? $this->code;
    }

    public function getCodeHistoryAttribute()
    {
        $matches = $this->codeMatches()->get();
        return $matches->map(function ($match) {
            return [
                'applied_code' => $match->applied_code,
                'status' => $match->status,
                'matched_with' => $match->sourceCourse?->name,
                'matched_with_id' => $match->sourceCourse?->id,
                'project_target' => $match->target_project,
                'note' => $match->note,
                'created_at' => $match->created_at,
            ];
        });
    }

    public function scopeFilterMatches(Builder $query, array $filters)
    {
        return $query
            ->when(!empty($filters['project_source']), function ($q) use ($filters) {
                $q->where('project_source', $filters['project_source']);
            })
            ->when(!empty($filters['category_id']), function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            })
            ->when(isset($filters['online']), function ($q) use ($filters) {
                $q->where('online', $filters['online']);
            })
            ->when(!empty($filters['course_id']), function ($q) use ($filters) {
                $q->where('id', $filters['course_id']);
            });
    }
}