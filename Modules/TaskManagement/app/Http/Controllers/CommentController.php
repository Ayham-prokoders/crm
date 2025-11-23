<?php

namespace Modules\TaskManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\TaskManagement\Models\{Task};
use Modules\TaskManagement\Models\Comment;
use Illuminate\Support\Facades\Notification;
use Modules\TaskManagement\Models\BoardTask;
use Modules\TaskManagement\Models\ExternalTask;
use Modules\TaskManagement\Models\ContactDirectory;
use Modules\TaskManagement\Transformers\CommentResource;
use Modules\TaskManagement\Notifications\CommentMentionNotification;

class CommentController extends Controller
{
    private function resolveModel(string $type, $id)
    {
        return match ($type) {
            'tasks' => Task::findOrFail($id),
            'external-tasks' => ExternalTask::findOrFail($id),
            'board-tasks' => BoardTask::findOrFail($id),
            default => abort(404, 'Invalid type'),
        };
    }
    public function store(Request $request, string $type, $id)
    {
        $model = $this->resolveModel($type, $id);

        $request->validate([
            'content' => 'required|string',
            'attachments' => 'nullable|array',
        ]);

        $mentions = $this->extractMentionIds($request->content);

        $attachments = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('comments', 'public');
            }
        } elseif (is_array($request->attachments)) {
            $attachments = $request->attachments;
        }

        $comment = $model->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'mentions' => $mentions,
            'attachments' => $attachments,
        ]);

        $this->handleCommentMentions($model, $mentions);

        return ResponseHelper::create(new CommentResource($comment));
    }


    public function index(string $type, $id)
    {
        $model = $this->resolveModel($type, $id);

        $comments = $model->comments()->latest()->get();

        return ResponseHelper::success(CommentResource::collection($comments));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return ResponseHelper::success();
    }

    private function extractMentionIds($text)
    {
        preg_match_all('/@(\d+)/', $text, $matches);
        return $matches[1] ?? [];
    }

    private function handleCommentMentions($commentable, $mentions)
    {
        $contacts = ContactDirectory::whereIn('id', $mentions)->get();

        if ($contacts->isEmpty()) {
            return;
        }

        $contactEmails = $contacts->pluck('email')->toArray();

        $users = User::whereIn('email', $contactEmails)->get();

        foreach ($users as $user) {
            Notification::send($user, new CommentMentionNotification($user->id, $commentable));
        }
    }


}
