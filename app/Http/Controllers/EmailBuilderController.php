<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MailLog;
use App\Models\EmailBuilder;
use Illuminate\Http\Request;
use App\Enums\TemplateCategoryEnum;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\EmailBuilderRequest;
use App\Mail\SendUserMail;

class EmailBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(EmailBuilder::class)
        ->allowedFilters(['category'])
        ->allowedSorts(['category', 'created_at']);

        $emails = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        return ResponseHelper::success($emails);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailBuilderRequest $request)
    {
        $email = EmailBuilder::create($request->validated());
        return ResponseHelper::create($email);
    }

    /**
     * Show the specified resource.
     */
    public function show(EmailBuilder $email)
    {
        return ResponseHelper::success($email);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailBuilderRequest $request, EmailBuilder $email)
    {
        $email->update($request->validated());

        return ResponseHelper::success($email);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailBuilder $email)
    {
        return ResponseHelper::success($email->delete());
    }

    public function getCategories()
    {
        return ResponseHelper::success(TemplateCategoryEnum::getValues());
    }

    public function sendEmails(Request $request){

        $validatedData = $request->validate([
            'type' => 'required',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'template' => 'required',
        ]);
        $template = EmailBuilder::findOrFail($validatedData['template']['id']);
        $users = User::whereIn('id', $validatedData['recipients'])->get();
        $sent_by= Auth::id();
        $template = $validatedData['template'];
        $subject = $template['subject'];

        foreach ($users as $user) {

        // Step 1: Decode JSON string
        $rawBody = $template['html_body'] ?? '';

        // If it's wrapped in double quotes, it's JSON-encoded
        if (str_starts_with($rawBody, '"')) {
            $rawBody = json_decode($rawBody);
        }

        // Step 2: Remove leftover slashes (if still present)
        $rawBody = stripslashes($rawBody);

        // Step 3: Replace placeholders
        $replacedBody = str_replace(
            ['{{name}}'],
            [$user->name],
            $rawBody
        );

        // Step 4: Decode any HTML entities
        $replacedBody = html_entity_decode($replacedBody);

            if ($template['status'] == 0) {
                // Save as draft
                MailLog::create([
                    'recipient' => $user->email,
                    'name' => $user->name,
                    'subject' => $template['subject'] ?? 'No Subject',
                    'body' => $replacedBody,
                    'status' => 0,
                    'user_id' => $sent_by,
                    'attachments' => null,
                ]);
            } else {
                Mail::to($user->email)->queue(
                    new SendUserMail($user, $sent_by, $replacedBody, $subject)
                );
            }
        }
        return ResponseHelper::success();
    }
    public function syncData()
    {
        $emails = EmailBuilder::get();
        return ResponseHelper::success($emails);
    }

}

