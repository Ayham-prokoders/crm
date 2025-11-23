<?php

namespace Modules\TrainerManagement\Traits;

use App\Models\User;
use Modules\Lms\Models\{City, Category};
use Modules\TrainerManagement\Models\Topic;

trait InstructorRelations {
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class,'location');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'instructor_topic', 'instructor_id', 'topic_id');
    }
}