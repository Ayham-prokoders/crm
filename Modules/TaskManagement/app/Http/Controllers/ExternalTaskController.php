<?php

namespace Modules\TaskManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Notification;
use Modules\TaskManagement\Models\ExternalTask;
use Modules\TaskManagement\Models\ContactDirectory;
use Modules\TaskManagement\Notifications\AssignNotification;
use Modules\TaskManagement\Http\Requests\ExternalTaskRequest;
use Modules\TaskManagement\Transformers\ExternalTaskResource;
use Modules\TaskManagement\Transformers\ExternalTaskCollection;
use Modules\TaskManagement\Notifications\CommentMentionNotification;

class ExternalTaskController extends Controller
{
   public function store(ExternalTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['title'] = $validated['course_name'] . ' - ' . $validated['course_duration'];

        $task = ExternalTask::create($validated);

        $this->handleAssignNotifications($task);
        // $this->handleRequirementMentions($task, $validated['requirements'] ?? '');

        return ResponseHelper::create(new ExternalTaskResource($task));
    }


    public function show(ExternalTask $task)
    {
        return new ExternalTaskResource($task);
    }

    public function index(Request $request)
    {
        $query = QueryBuilder::for(ExternalTask::class)
            ->allowedFilters([
                AllowedFilter::exact('contact'),
                AllowedFilter::exact('status'),
                'created_at'
            ])
            ->allowedSorts(['created_at'])
            ->orderByDesc('created_at');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $tasks = $query->paginate($request->per_page);

        return ResponseHelper::success(new ExternalTaskCollection($tasks));
    }

    public function update(ExternalTaskRequest $request, ExternalTask $task)
    {
        $validated = $request->validated();

         if ($validated['status'] == 'canceled' && empty($validated['cancellation_reason'])) {
            return ResponseHelper::operationFail('cancellation reason required');
        }
        $validated['title'] = $validated['course_name'] . ' - ' . $validated['course_duration'];

        $task->update($validated);

        // $this->handleRequirementMentions($task, $validated['requirements'] ?? '');

        return ResponseHelper::success(new ExternalTaskResource($task));
    }

    public function destroy(ExternalTask $task)
    {
        $task->delete();

        return ResponseHelper::success();
    }

    public function daysWithTasks(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $tasks = ExternalTask::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->get();

        $days = $tasks->groupBy(function ($task) {
            return $task->created_at->format('Y-m-d');
        })->keys();

        return ResponseHelper::success($days);
    }

    private function extractMentionIds($text)
    {
        preg_match_all('/@(\d+)/', $text, $matches);
        return $matches[1] ?? [];
    }

     private function handleAssignNotifications(ExternalTask $task)
    {
        if (is_array($task->contact)) {
            foreach ($task->contact as $contactId) {
                $contact = ContactDirectory::find($contactId);
                if ($contact) {
                    $user = User::where('email', $contact->email)->first();
                    if ($user) {
                        Notification::send($user, new AssignNotification($user->id, $task));
                    }
                }
            }
        }
    }

    private function handleRequirementMentions(ExternalTask $task, $text)
    {
        $mentions = $this->extractMentionIds($text);
        $task->requirement_mentions = $mentions;
        $task->save();

        foreach ($mentions as $contactId) {
            $contact = ContactDirectory::find($contactId);
            if ($contact) {
                $user = User::where('email', $contact->email)->first();
                if ($user) {
                    Notification::send($user, new CommentMentionNotification($user->id, $task));
                }
            }
        }
    }
}
