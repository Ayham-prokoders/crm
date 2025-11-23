<?php

namespace Modules\TaskManagement\Models;

use App\Models\User;
use App\Traits\LogsActionHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TaskManagement\Database\Factories\CommentFactory;

class Comment extends Model
{
    use HasFactory , LogsActionHistory;
    protected static string $historyModule = 'Comments';
    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = ['task_id', 'user_id', 'content', 'mentions', 'attachments'];

    protected $casts = [
        'mentions' => 'array',
        'attachments' => 'array',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
