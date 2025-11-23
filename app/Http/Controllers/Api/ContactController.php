<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Modules\DealManagement\Models\Invoice;
use App\Models\ModelAttachment;
use App\Models\ModelNote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * get trainee's deals
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deals($id)
    {
        $user = User::findOrFail($id);
        $count = $user->deals()->count();
        $deals = $user->deals()->with('company')->get();
        $data = [
            'count' => $count,
            'data' => $deals
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's courses
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function courses($id)
    {
        $user = User::findOrFail($id);
        $courses = $user->trainees()->with('course')->get();
        $data = [
            'count' => $courses->count(),
            'data' => $courses
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's invoices
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoices($id)
    {
        $user = User::findOrFail($id);
        $invoices = Invoice::whereHas('deal.users', function ($q) use ($id) {
            $q->where('users.id', $id);
        })
        ->with('deal')
        ->get();
        $data = [
            'count' => $invoices->count(),
            'data' => $invoices
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's certificates
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function certificates($id)
    {
        $user = User::findOrFail($id);
        $certificates = $user->certificates()->get();
        $data = [
            'count' => $certificates->count(),
            'data' => $certificates
        ];
        return ResponseHelper::success($data);
    }

    /**
     * get trainee's notes
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function notes($id)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $notes = $user->modelNotes()->with('author')->latest()->get();

        return ResponseHelper::success([
            'count' => $notes->count(),
            'data' => $notes,
        ]);
    }

    /**
     * store trainee's note
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNote(Request $request, $id)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $note = $user->modelNotes()->create([
            'content' => $data['content'],
            'user_id' => Auth::id(),
        ]);

        return ResponseHelper::create($note->load('author'));
    }

    /**
     * update trainee's note
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @param string $noteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNote(Request $request, $id, $noteId)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $note = $user->modelNotes()->whereKey($noteId)->first();
        if (!$note) {
            return ResponseHelper::notFound();
        }

        $data = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $note->update([
            'content' => $data['content'],
        ]);

        return ResponseHelper::success($note->fresh('author'));
    }

    /**
     * delete trainee's note
     * @param string $id
     * @param string $noteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNote($id, $noteId)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $note = $user->modelNotes()->whereKey($noteId)->first();
        if (!$note) {
            return ResponseHelper::notFound();
        }

        $note->delete();

        return ResponseHelper::success();
    }

    /**
     * get trainee's attachments
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachments($id)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $attachments = $user->modelAttachments()->with('uploader')->latest()->get();

        return ResponseHelper::success([
            'count' => $attachments->count(),
            'data' => $attachments,
        ]);
    }

    /**
     * store trainee's attachment
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAttachment(Request $request, $id)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $attachment = $user->modelAttachments()->create([
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'user_id' => Auth::id(),
        ]);

        return ResponseHelper::create($attachment->load('uploader'));
    }

    /**
     * update trainee's attachment
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @param string $attachmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAttachment(Request $request, $id, $attachmentId)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $attachment = $user->modelAttachments()->whereKey($attachmentId)->first();
        if (!$attachment) {
            return ResponseHelper::notFound();
        }

        $data = $request->validate([
            'file' => ['sometimes', 'file'],
            'original_name' => ['sometimes', 'string'],
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($attachment->path);

            $file = $request->file('file');
            $attachment->path = $file->store('attachments', 'public');
            $attachment->original_name = $file->getClientOriginalName();
            $attachment->mime_type = $file->getClientMimeType();
            $attachment->size = $file->getSize();
            $attachment->user_id = Auth::id();
        }

        if (array_key_exists('original_name', $data)) {
            $attachment->original_name = $data['original_name'];
        }

        $attachment->save();

        return ResponseHelper::success($attachment->fresh('uploader'));
    }

    /**
     * delete trainee's attachment
     * @param string $id
     * @param string $attachmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAttachment($id, $attachmentId)
    {
        $user = $this->getTrainee($id);
        if (!$user) {
            return ResponseHelper::notFound();
        }

        $attachment = $user->modelAttachments()->whereKey($attachmentId)->first();
        if (!$attachment) {
            return ResponseHelper::notFound();
        }

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return ResponseHelper::success();
    }

    private function getTrainee($id): ?User
    {
        $user = User::find($id);

        if (!$user || !$user->hasRole('trainee')) {
            return null;
        }

        return $user;
    }




}
