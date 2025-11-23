<?php

namespace Modules\TaskManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Modules\TaskManagement\Enums\TaskStatus;
use Modules\TaskManagement\Models\BoardTask;
use Modules\TaskManagement\Http\Requests\BoardTask\BoardTaskRequest;
use Modules\TaskManagement\Http\Requests\BoardTask\UpdateBoardTaskRequest;

class BoardTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = BoardTask::get();
        return ResponseHelper::success($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoardTaskRequest $request)
    {
        try {
            $data = $request->validated();
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachments[] = $file->store('board_task', 'public');
                }
            } elseif (is_array($request->attachments)) {
                $attachments = $request->attachments;
            }
            $data['attachments'] = $attachments;
            $task = BoardTask::create($data);
            return response()->json([
                "data" => $task,
                "message" => "operatoin sucess",
                "code" => 201
            ]);
        } catch (\Throwable $e) {
            \Log::error('BoardTask.store exception', ['message' => $e->getMessage(),'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => false,'message' => 'Server Error','error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(BoardTask $board_task)
    {
        return ResponseHelper::success($board_task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoardTaskRequest $request, BoardTask $board_task)
    {
        try {
            $data = $request->validated();
            $attachments = $board_task->attachments ?? [];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachments[] = $file->store('board_task', 'public');
                }
            } elseif (is_array($request->attachments)) {
                $attachments = array_merge($attachments, $request->attachments);
            }

            $data['attachments'] = $attachments;
            $board_task->update($data);
            return response()->json([
                "data" => $board_task,
                "message" => "operatoin sucess",
                "code" => 200
            ]);
        } catch (\Throwable $e) {
            \Log::error('BoardTask.store exception', ['message' => $e->getMessage(),'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => false,'message' => 'Server Error','error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardTask $board_task)
    {
        $board_task->delete();
        return ResponseHelper::success();
    }

    /**
     * Update the status of a task (Kanban move)
     */
    public function changeStatus(Request $request, BoardTask $board_task)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_column(TaskStatus::cases(), 'value'))],
        ]);

        $board_task->status = $request->status;
        $board_task->save();

        return response()->json([
            "data" => $board_task,
            "message" => "operatoin sucess",
            "code" => 200
        ]);
    }

}
