<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\TaskManagement\Models\Comment;

class MigrateCommentTaskRelation extends Command
{
    protected $signature = 'migrate:comment-tasks';
    protected $description = 'Convert old comments from task_id to polymorphic relation';

    public function handle()
    {
        $this->info("Starting comment migration...");

        $count = Comment::whereNotNull('task_id')->count();

        if ($count === 0) {
            $this->info("No comments to migrate.");
            return;
        }

        Comment::whereNotNull('task_id')->chunkById(100, function ($comments) {
            foreach ($comments as $comment) {
                $comment->commentable_id = $comment->task_id;
                $comment->commentable_type = \Modules\TaskManagement\Models\Task::class;
                $comment->save();
            }
        });

        $this->info("Migration complete. All comments have been updated.");
    }
}
