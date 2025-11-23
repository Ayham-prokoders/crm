<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\LmsSyncService;
use App\Http\Requests\NoteRequest;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Notifications\NoteTelegramNotification;
use App\Notifications\{NoteNotification,AlertNotification};

class NoteController extends Controller
{
    public function index(Request $request)
{
    $currentUser = Auth::user();
    $currentRole = $currentUser->current_role_id;
    $role = Role::find($currentRole);

    $query = QueryBuilder::for(Note::class)
        ->allowedFilters(['user_id', 'type', 'level'])
        ->allowedSorts(['user_id', 'created_at'])
        ->when($request->input('subject'), function ($query, $subject) {
            return $query->where('subject', 'like', "%{$subject}%");
        })
        ->when($request->input('message'), function ($query, $message) {
            return $query->where('message', 'like', "%{$message}%");
        });

    // Filter notes based on the user's role, unless they are an admin
    if ($role->name !== 'admin') {
        $query->where(function($query) use ($currentUser, $currentRole) {
            $query->whereJsonContains('role_ids', $currentRole)
                  ->orWhereHas('recipients', function ($q) use ($currentUser) {
                      $q->where('user_id', $currentUser->id);
                  });
        });
    }

    // Paginate or get the notes based on the request
    $notes = $request->input('limit')
        ? $query->paginate($request->input('limit'))
        : $query->get();

    $data = [
        'notes' => NoteResource::collection($notes),
    ];

    // Include pagination details if limit is provided
    if ($request->input('limit')) {
        $data['pagination'] = [
            'total' => $notes->total(),
            'per_page' => $notes->perPage(),
            'current_page' => $notes->currentPage(),
            'last_page' => $notes->lastPage(),
            'from' => $notes->firstItem(),
            'to' => $notes->lastItem(),
            'links' => [
                'first' => $notes->url(1),
                'last' => $notes->url($notes->lastPage()),
                'prev' => $notes->previousPageUrl(),
                'next' => $notes->nextPageUrl(),
            ],
        ];
    }

    return ResponseHelper::success($data);
}


public function SendedNotes(Request $request)
{
    $currentUser = Auth::user();
    $currentRole = $currentUser->current_role_id;
    $role = Role::find($currentRole);

    $query = QueryBuilder::for(Note::class)
        ->allowedFilters(['user_id', 'type', 'level'])
        ->allowedSorts(['user_id', 'created_at'])
        ->when($request->input('subject'), function ($query, $subject) {
            return $query->where('subject', 'like', "%{$subject}%");
        })
        ->when($request->input('message'), function ($query, $message) {
            return $query->where('message', 'like', "%{$message}%");
        });

    // Filter notes based on the user's role, unless they are an admin
    if ($role->name !== 'admin') {
        $query->where('user_id', $currentUser->id);
    }

    // Paginate or get the notes based on the request
    $notes = $request->input('limit')
        ? $query->paginate($request->input('limit'))
        : $query->get();

    $data = [
        'notes' => NoteResource::collection($notes),
    ];

    // Include pagination details if limit is provided
    if ($request->input('limit')) {
        $data['pagination'] = [
            'total' => $notes->total(),
            'per_page' => $notes->perPage(),
            'current_page' => $notes->currentPage(),
            'last_page' => $notes->lastPage(),
            'from' => $notes->firstItem(),
            'to' => $notes->lastItem(),
            'links' => [
                'first' => $notes->url(1),
                'last' => $notes->url($notes->lastPage()),
                'prev' => $notes->previousPageUrl(),
                'next' => $notes->nextPageUrl(),
            ],
        ];
    }

    return ResponseHelper::success($data);
}

        //    if (!in_array($role->name, ['admin', 'supervisor'])) {
        //        return ResponseHelper::authorizationFail();
        //    }
        public function store(NoteRequest $request)
        {
            $user = Auth::user();
            $currentRole = $user->current_role_id;
            $role = Role::find($currentRole);

            $note = Note::create([
                'user_id' => $user->id,
                'type' => $request->input('type'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'level' => $request->input('level'),
            ]);

            $platforms = $request->input('platforms');

            // Handle recipients (users)
            if ($request->has('recipients')) {
                $recipients = $request->input('recipients');
                $note->recipients()->sync($recipients);
                foreach ($recipients as $recipientId) {
                    $this->sendNotificationsToUser($recipientId, $note, $platforms);
                }
            }

            // Handle roles if any
            if ($request->has('roles')) {
                $roles = $request->input('roles');
                $note->update(['role_ids' => json_encode($roles)]);

                $roleUsers = User::whereHas('roles', function($query) use ($roles) {
                    $query->whereIn('id', $roles);
                })->get();
                $note->recipients()->sync($roleUsers);
                foreach ($roleUsers as $roleUser) {
                    $this->sendNotificationsToUser($roleUser->id, $note, $platforms);
                }
            }

            dispatch(function () use ($note) {
                LmsSyncService::sync($note, 'update');
            })->afterResponse();


            return ResponseHelper::create(new NoteResource($note));
        }



    public function update(NoteRequest $request, Note $note)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        $note->update([
           'user_id'=>$user->id,
            'type' => $request->input('type'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'level' => $request->input('level'),
        ]);
        return ResponseHelper::success(new NoteResource($note));
    }

    /**
     * Remove the specified Note from storage.
     *
     * @param  \App\Models\Note  $Note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        if ($note->user_id !== $user->id){
            return ResponseHelper::operationFail('you can only delete your note');
        }
        $note->delete();
        return ResponseHelper::success();
    }

    public function ackNote(Request $request)
    {
        $noteId = $request->note_id;
        $userId = $request->user_id;

        // Find the note and user
        $note = Note::find($noteId);
        $user = User::find($userId);

        if (!$note || !$user) {
            return ResponseHelper::DataNotFound();
        }

        // Update the acknowledge field for the specific user-note pair
        $note->recipients()->updateExistingPivot($userId, ['acknowledge' => 1]);

        dispatch(function () use ($note) {
            LmsSyncService::sync($note, 'update');
        })->afterResponse();


        return ResponseHelper::success();
    }

    protected function sendNotificationsToUser($userId, Note $note, array $platforms)
    {
        $user = User::find($userId)->firstOrFail();
        foreach ($platforms as $platform) {
            $this->sendNote($user, $note, $platform);
        }
    }

    protected function sendNote(User $user, Note $note, string $platform)
    {
            switch ($platform) {
                case 'crm':
                    // $user->notify(new NoteNotification($note , $user->id));
                    if ($note->type === 'Alert') {
                        $user->notify(new AlertNotification($note, $user->id));
                    } else {
                        $user->notify(new NoteNotification($note, $user->id));
                    }
                    break;
                case 'telegram':
                    if($user->telegram_chat_id){
                    $user->notify(new NoteTelegramNotification($note , $user->id));
                }
                    break;
                case 'sms':
                    if($user->phone)
                    // $user->notify(new NoteSmsNotification($note , $user->id));
                    break;
                    default:
                \Log::warning("Unknown notification platform: $platform");
                break;
            }

    }
}
