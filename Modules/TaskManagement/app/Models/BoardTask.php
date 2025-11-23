<?php

namespace Modules\TaskManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\TaskManagement\Enums\IssueSource;
use Modules\TaskManagement\Models\Comment;
use Modules\TaskManagement\Enums\TaskLabel;
use Modules\TaskManagement\Enums\TaskStatus;
use Modules\TaskManagement\Enums\TaskPriority;
use Modules\TaskManagement\Enums\TaskRelatedTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TaskManagement\Database\Factories\BoardTaskFactory;

class BoardTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 'description', 'assign_to',
        'status', 'priority', 'related_to', 'label',
        'start_date', 'deadline','source_issue','attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'related_to' => TaskRelatedTo::class,
        'label' => TaskLabel::class,
        // 'source_issue' => IssueSource::class,
    ];

    /**
     * link with comment model
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Comment, BoardTask>
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
