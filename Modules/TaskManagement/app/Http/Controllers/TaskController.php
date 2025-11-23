<?php

namespace Modules\TaskManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Modules\DealManagement\Models\Deal;
use Modules\TaskManagement\Models\Task;
use Illuminate\Support\Facades\Notification;
use Modules\TaskManagement\Models\ContactDirectory;
use Modules\DealManagement\Transformers\DealResource;
use Modules\TaskManagement\Http\Requests\TaskRequest;
use Modules\TaskManagement\Transformers\TaskResource;
use Modules\TaskManagement\Transformers\TaskCollection;
use Modules\TaskManagement\Notifications\AssignNotification;
use Modules\TaskManagement\Notifications\CommentMentionNotification;

class TaskController extends Controller
{
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();
        $deal = Deal::with(['course', 'externalCourse'])->find($validated['deal_id']);
        if (!$deal) {
            return ResponseHelper::operationFail('Deal not found');
        }
       if ($deal->course_type === 'custom') {
            $courseName = $deal->externalCourse ? $deal->externalCourse->name : 'Unknown External Course';
            $courseDuration = $deal->externalCourse ? $deal->externalCourse->duration : '';
        } else {
            $courseName = $deal->course ? $deal->course->name : 'Unknown Course';
            $courseDuration = $deal->course ? $deal->course->duration : '';
        }

        $validated['title'] = $courseName . ' - ' . $courseDuration;

        $task = Task::create($validated);

        if ($request->has('trainees')) {
            $task->trainees()->sync($request->trainees);
        }

        $this->handleAssignNotifications($task);
        // $this->handleRequirementMentions($task, $validated['requirements'] ?? '');

        return ResponseHelper::create(new TaskResource($task));
    }


    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function index(Request $request)
    {
        $query = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('deal_id'),
                AllowedFilter::exact('contact_id'),
                AllowedFilter::exact('status'),
                'created_at'
            ])
            ->allowedSorts(['created_at'])
            ->orderByDesc('created_at');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $tasks = $query->paginate($request->per_page);

        return ResponseHelper::success(new TaskCollection($tasks));
    }


    public function daysWithTasks(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $courseType = $request->input('course_type');

        $tasks = Task::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->when($courseType, function ($query, $courseType) {
                            $query->whereHas('deal', function ($q) use ($courseType) {
                                $q->where('course_type', $courseType);
                            });
                        })
                    ->get();

        $days = $tasks->groupBy(function ($task) {
            return $task->created_at->format('Y-m-d');
        })->keys();

        return ResponseHelper::success($days);
    }

    public function list(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
        ->allowedFilters([
            AllowedFilter::exact('deal_id'),
            AllowedFilter::exact('assign_to'),
            // AllowedFilter::exact('task_for'),
            AllowedFilter::exact('status'),
            'created_at'
        ])
            ->allowedSorts(['created_at'])
            ->orderByDesc('created_at')
            ->get();

        return ResponseHelper::success(TaskResource::collection($tasks));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $validated = $request->validated();

         if ($validated['status'] == 'canceled' && empty($validated['cancellation_reason'])) {
            return ResponseHelper::operationFail('cancellation reason required');
        }
        $deal = Deal::with(['course', 'externalCourse'])->find($validated['deal_id']);
        if (!$deal) {
            return ResponseHelper::operationFail('Deal not found');
        }
       if ($deal->course_type == 'custom') {
            $courseName = $deal->externalCourse ? $deal->externalCourse->name : 'Unknown External Course';
            $courseDuration = $deal->externalCourse ? $deal->externalCourse->duration : '';
        } else {
            $courseName = $deal->course ? $deal->course->name : 'Unknown Course';
            $courseDuration = $deal->course ? $deal->course->duration : '';
        }

        $validated['title'] = $courseName . ' - ' . $courseDuration;

        $task->update($validated);

        if ($request->has('trainees')) {
            $task->trainees()->sync($request->trainees);
        }

        // $this->handleRequirementMentions($task, $validated['requirements'] ?? '');

        return ResponseHelper::success(new TaskResource($task));
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return ResponseHelper::success();
    }

    private function extractMentionIds($text)
    {
        preg_match_all('/@(\d+)/', $text, $matches);
        return $matches[1] ?? [];
    }

    private function handleAssignNotifications(Task $task)
    {
        if (is_array($task->contact_id)) {
            foreach ($task->contact_id as $contactId) {
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

    private function handleRequirementMentions(Task $task, $text)
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


    // إحصائيات حسب الحالة
   public function statistics(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $query = Task::query();

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        $tasks = $query->get();

        $totalTasks = $tasks->count();

        $allStatuses = ['open', 'inprogress', 'pending', 'done', 'canceled'];
        $statusCounts = collect($allStatuses)->mapWithKeys(function ($status) use ($tasks) {
            return [$status => $tasks->where('status', $status)->count()];
        });

        $statusPercentages = $statusCounts->map(function ($count) use ($totalTasks) {
            return $totalTasks > 0 ? ($count / $totalTasks) * 100 : 0;
        });

        $statistics = $statusCounts->map(function ($count, $status) use ($statusPercentages) {
            return [
                'status' => $status,
                'count' => $count,
                'percentage' => round($statusPercentages[$status], 2)
            ];
        })->values();

         $tasksByStatus = $tasks->groupBy('status')->map(function ($group)  {
            return TaskResource::collection($group);
        });

        $rejectionReasons = null;
        if ($statusCounts['canceled'] ?? 0 > 0) {
            $rejectionReasons = $tasks->where('status', 'canceled')
                ->groupBy('cancellation_reason')
                ->map(fn($group) => $group->count());
        }

        $response = [
            'statistics' => $statistics,
            'tasks_by_status' => $tasksByStatus,
        ];

        if ($rejectionReasons) {
            $response['rejection_reasons'] = $rejectionReasons;
        }

        return ResponseHelper::success($response);
    }



}
