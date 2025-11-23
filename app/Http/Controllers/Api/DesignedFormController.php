<?php

namespace App\Http\Controllers\Api;

use I18N_Arabic;
use App\Models\Answer;
use App\Models\MailLog;
use App\Models\FailedSync;
use App\Models\GuestSurvey;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FooterSetting;
use App\Services\LmsSyncService;
use App\Mail\SendDesignedFormMail;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use Psy\Readline\Hoa\ConsoleOutput;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Spatie\QueryBuilder\QueryBuilder;
use App\Notifications\FormNotification;
use App\Http\Requests\SurveyRateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\DesignedFormRequest;
use App\Http\Resources\GuestSurveyResource;
use Modules\Lms\Models\{Company ,City ,Category ,Classe};
use App\Models\{DesignedForm, Question, User, AnswerdForm};
use App\Http\Resources\{DesignedFormResource, AnsweredFormResource};

class DesignedFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        $lang = $request->header('lang');
        // Define the base query
        $query = QueryBuilder::for(DesignedForm::class)
            ->allowedFilters(['title', 'classe_id', 'type', 'id'])
            ->allowedSorts(['title', 'created_at'])
            ->where('lang_code', $lang ?? 'en');

        // Check user roles and apply appropriate filtering
        if (in_array($role->name, ['admin', 'supervisor'])) {
            // Admin and supervisor can see all forms, no additional filtering needed
        } elseif ($role->name == 'trainer') {
            // Trainer can only see forms assigned to their classes
            $query->whereIn('classe_id', Auth::user()->trainerClasses()->pluck('id'));
        }
        // elseif ($role->name=='companySupervisor') {
        //     // Check if the user is a trainer for the specified class
        //     $userCompany=User::where('company_id',$user->company_id)->get()->pluck('id');
        //     // Retrieve all attendance records for the specified class
        // }
        else {
            // Others are not authorized to access forms
            return ResponseHelper::authorizationFail();
        }

        // Apply pagination if limit is provided
        $forms = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        // Prepare the response data
        $data = [
            'forms' => DesignedFormResource::collection($forms),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $forms->total(),
                'per_page' => $forms->perPage(),
                'current_page' => $forms->currentPage(),
                'last_page' => $forms->lastPage(),
                'from' => $forms->firstItem(),
                'to' => $forms->lastItem(),
                'links' => [
                    'first' => $forms->url(1),
                    'last' => $forms->url($forms->lastPage()),
                    'prev' => $forms->previousPageUrl(),
                    'next' => $forms->nextPageUrl(),
                ],
            ];
        }

        // Return success response with data
        return ResponseHelper::success($data);
    }


    public function getFormByClassId(Request $request)
    {
        $classId = $request->input('class_id');
        // Retrieve the class by class_id
        $class = Classe::find($classId);
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);
        // Check if the class exists
        if (!$class) {
            // Handle case where class does not exist
            return ResponseHelper::DataNotFound();
        }

        // Check if the authenticated user is a trainee in the class
        if ($role == 'trainee') {
            $isTrainee = $class->trainees()->where('user_id', auth()->id())->exists();
            if (!$isTrainee) {
                // Return error response indicating unauthorized access
                return ResponseHelper::authorizationFail();
            }
        } elseif ($role->name == 'trainer') {
            $isTrainer = $class->trainer()->where('id', Auth::id())->exists();
            if (!$isTrainer) {
                // Return error response indicating unauthorized access
                return ResponseHelper::authorizationFail();
            }
        } elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            return ResponseHelper::authorizationFail();
        }
        // Retrieve the form related to the class
        $form = $class->designedForms()->first();

        // Check if a form exists for the class
        if (!$form) {
            // Handle case where no form is found
            return ResponseHelper::DataNotFound();
        }

        // Return success response with the form data
        return ResponseHelper::success(new DesignedFormResource($form));
    }



    public function getFormById(Request $request)
    {
        // Retrieve the form by form_id
        $form = DesignedForm::find($request->input('form_id'));

        // Return success response with the form data
        return ResponseHelper::success(new DesignedFormResource($form));
    }

    public function getFormBySlug(Request $request)
    {
        $form = DesignedForm::where('slug', $request->input('slug'))
            ->firstOrFail();
        // Return success response with the form data
        return ResponseHelper::success(new DesignedFormResource($form));
    }
    public function getFormNotAnswered(Request $request)
    {
        $userId = Auth::user()->id;

        // Retrieve the forms for the authenticated user where answered is false
        // $forms = $userId->forms()->wherePivot('answered', false)->get();
        $forms = AnswerdForm::where('user_id', $userId)->where('answered', 0)->get();

        // return $forms;
        // Return success response with the forms data
        return ResponseHelper::success(AnsweredFormResource::collection($forms));
    }

    // public function getAnsweredForm(Request $request)
    // {
    //     // Retrieve the answered auth surveys with filters and sorts
    //     $authSurveys = QueryBuilder::for(AnswerdForm::class)
    //         ->allowedFilters(['designed_form_id', 'user_id'])
    //         ->allowedSorts(['designed_form_id', 'created_at'])
    //         ->where('answered', 1)
    //         ->get();

    //     // Retrieve the guest surveys with filters and sorts
    //     $guestSurveys = QueryBuilder::for(GuestSurvey::class)
    //         ->allowedFilters(['designed_form_id'])
    //         ->allowedSorts(['designed_form_id', 'created_at'])
    //         ->with(['designedForm', 'guestSurveyAnswers.question'])
    //         ->get();

    //     // Format the response using resources
    //     $forms = [
    //         'authSurveys' => AnsweredFormResource::collection($authSurveys),
    //         'guestSurveys' => GuestSurveyResource::collection($guestSurveys),
    //     ];

    //     // Return success response with the forms data
    //     return ResponseHelper::success($forms);
    // }

    // public function getAnsweredForm(Request $request)
    // {
    //     // Retrieve the answered auth surveys with filters and sorts
    //     $authSurveys = QueryBuilder::for(AnswerdForm::class)
    //         ->allowedFilters(['designed_form_id'])
    //         ->allowedSorts(['designed_form_id', 'created_at'])
    //         ->where('answered', 1)
    //         ->get();

    //     // Retrieve the guest surveys with filters and sorts
    //     $guestSurveys = QueryBuilder::for(GuestSurvey::class)
    //         ->allowedFilters(['designed_form_id'])
    //         ->allowedSorts(['designed_form_id', 'created_at'])
    //         ->get();

    //     // Transform both collections into their respective resources
    //     $authSurveyResources = AnsweredFormResource::collection($authSurveys);
    //     $guestSurveyResources = GuestSurveyResource::collection($guestSurveys);

    //     // Merge the two collections into one array
    //     $mergedSurveys = $authSurveyResources->toArray($request)
    //         + $guestSurveyResources->toArray($request);

    //     // Return success response with the merged forms data
    //     return ResponseHelper::success($mergedSurveys);
    // }

    public function getAnsweredForm(Request $request)
    {
        // Check if the 'user' filter exists in the request
        $userFilter = $request->input('user');

        // Retrieve the answered auth surveys with filters and sorts
        $authSurveysQuery = QueryBuilder::for(AnswerdForm::class)
            ->allowedFilters(['designed_form_id'])
            ->allowedSorts(['designed_form_id', 'created_at'])
            ->where('answered', 1)
            ->with(['designedForm', 'user']);

        // If a user filter exists, apply it to user_id
        if ($userFilter) {
            $authSurveysQuery->whereHas('user', function ($query) use ($userFilter) {
                $query->where('id', $userFilter);
            });
        }

        $authSurveys = $authSurveysQuery->get();

        // Retrieve the guest surveys with filters and sorts
        $guestSurveysQuery = QueryBuilder::for(GuestSurvey::class)
            ->allowedFilters(['designed_form_id'])
            ->allowedSorts(['designed_form_id', 'created_at'])
            ->with('designedForm');

        // If a user filter exists, apply it to email
        if ($userFilter) {
            $guestSurveysQuery->where('email', $userFilter);
        }

        $guestSurveys = $guestSurveysQuery->get();

        // Transform both collections into their respective resources
        $authSurveyResources = AnsweredFormResource::collection($authSurveys);
        $guestSurveyResources = GuestSurveyResource::collection($guestSurveys);

        // Merge
        // $mergedSurveys = $authSurveyResources->toArray($request)
        //     + $guestSurveyResources->toArray($request);

        $mergedSurveys = array_merge(
            $authSurveyResources->toArray($request),
            $guestSurveyResources->toArray($request)
        );

        // return $mergedSurveys;
        // Return success response with the merged forms data
        return ResponseHelper::success($mergedSurveys);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(DesignedFormRequest $request)
    {

        // Check if the user is authorized to create forms
        // if (!in_array($role->name, ['admin', 'supervisor'])) {
        //     return ResponseHelper::authorizationFail();
        // }

        // Begin a database transaction
        DB::beginTransaction();

        try {

            // $baseSlug = Str::slug($request->title);
            $title = $request->title;

            $baseSlug = preg_replace('/[^A-Za-z0-9\p{Arabic}\s-]/u', '', $title);
            $baseSlug = preg_replace('/\s+/u', '-', trim($baseSlug));
            $baseSlug = mb_strtolower($baseSlug);

            if (empty($baseSlug)) {
                $baseSlug = Str::random(8);
            }

            $slug = $baseSlug;
            $counter = 1;

            while (DesignedForm::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            // Create the form
            $form = DesignedForm::create([
                'lang_code' => $request->header('lang') ?? 'en',
                'title' => $request->title,
                'slug' => $slug,
                'image' => $request->input('image'),
                'type' => $request->type,
                'description' => $request->description,
                'classe_id' => in_array($request->type, ['pre_course', 'after_course']) ? $request->classe_id : null,
            ]);


            // Create questions associated with the form
            foreach ($request->questions as $questionData) {
                $options = $questionData['options'] ? json_encode($questionData['options']) : null;

                $question = new Question([
                    'type' => $questionData['type'],
                    'question' => $questionData['question'],
                    'is_required' => $questionData['is_required'],
                    'options' => $options,
                ]);
                $form->questions()->save($question);
            }

            // Commit the transaction
            DB::commit();

            // Return success response with data
            return ResponseHelper::create(new DesignedFormResource($form));
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Return error response
            return ResponseHelper::operationFail();
        }
    }


    public function sendForm(Request $request){

        $validatedData = $request->validate([
            'form_id' => 'required|exists:designed_forms,id',
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'template' => 'required',
        ]);
        $form = DesignedForm::findOrFail($validatedData['form_id']);
        $users = User::whereIn('id', $validatedData['recipients'])->get();
        $form->recipients()->sync($users->pluck('id')->toArray());

        $sent_by= Auth::id();
        $template = $validatedData['template'];
        $subject = $template['subject'];

        foreach ($users as $user) {

            $encryptedId = Crypt::encrypt($user->id);
            $questionnaireLink = env('PROJECT_BACKEND') . '/survey/' . $form->slug . '?user=' . urlencode($encryptedId);

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
                ['{{name}}', '{{link}}'],
                [$user->name, '<a href="'.$questionnaireLink.'" target="_blank">Please take a few minutes to fill out this questionnaire.</a>'],
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
                    new SendDesignedFormMail($user, $sent_by, $form, $replacedBody, $subject)
                );
                $user->notify(new FormNotification($user->getRoleNames()->first(), $user->id));

            }
        }

        dispatch(function () use ($form) {
            LmsSyncService::syncById($form->id);
        })->afterResponse();


        return ResponseHelper::success();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DesignedForm  $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DesignedForm $form)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        // Check if the user is authorized to update forms
        // if (!in_array($role->name, ['admin', 'supervisor'])) {
        //     return ResponseHelper::authorizationFail();
        // }

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Determine recipients based on form type
            switch ($request->type) {
                case 'pre_course':
                case 'after_course':
                    $recipients = json_encode($this->getTraineesForClass($request->classe_id));
                    break;
                case 'with_role':
                    // Get users with the specified roles
                    $recipients = json_encode($this->getUsersByRoles($request->input('role_ids')));
                    break;
                default:
                    $recipients = json_encode($request->input('recipients'));
                    break;
            }
            $title = $request->title;

            $baseSlug = preg_replace('/[^A-Za-z0-9\p{Arabic}\s-]/u', '', $title);
            $baseSlug = preg_replace('/\s+/u', '-', trim($baseSlug));
            $baseSlug = mb_strtolower($baseSlug);

            if (empty($baseSlug)) {
                $baseSlug = Str::random(8);
            }
            // Update the form attributes
            $form->update([
                'lang_code' => $request->header('lang') ?? 'en',
                'title' => $request->title,
                'image' => $request->input('image'),
                'slug' => $baseSlug,
                'type' => $request->type,
                'description' => $request->description,
                'classe_id' => in_array($request->type, ['pre_course', 'after_course']) ? $request->classe_id : null,
                'recipients' => $recipients,
            ]);


            if (!is_array($recipients)) {
                $recipients = json_decode($recipients, true);
            }
            // Attach recipients to the form
            foreach ($recipients as $recipient) {
                $user = User::where('email', $recipient)->first();
                if ($user) {
                    $form->recipients()->attach($user->id);
                    $user->notify(new FormNotification($user->getRoleNames()->first(), $user->id));
                }
            }
            if ($form->type == 'with_role') {
                $form->roles()->sync($request->input('role_ids'));

            }
            // Delete existing questions associated with the form
            $form->questions()->delete();

            // Create new questions associated with the form
            foreach ($request->questions as $questionData) {
                $options = $questionData['options'] ? json_encode($questionData['options']) : null;

                $question = new Question([
                    'type' => $questionData['type'],
                    'question' => $questionData['question'],
                    'options' => $options,
                    'is_required' => $questionData['is_required'],
                ]);
                $form->questions()->save($question);
            }

            // Commit the transaction
            DB::commit();

            // Return success response with data
            return ResponseHelper::success(new DesignedFormResource($form));
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Return error response
            return ResponseHelper::operationFail();
        }
    }


    private function getTraineesForClass($classId)
    {
        // Retrieve the trainees for the given class
        $class = Classe::find($classId);
        if ($class) {
            return $class->trainees->pluck('email')->toArray();
        }
        return [];
    }

    private function getUsersByRoles($roleIds)
    {
        // Retrieve role names associated with the given role IDs
        $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        // Get users with the specified roles
        return User::role($roleNames)->pluck('email')->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DesignedForm  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(DesignedForm $form)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

        // Check if the user is authorized to create forms
        if ($role->name == 'trainer') {
            $classId = $form->classe_id;
            $isTrainer = Auth::user()->trainerClasses()->where('classes.id', $classId)->exists();
            if (!$isTrainer) {
                return ResponseHelper::authorizationFail();
            }
        } elseif (!in_array($role->name, ['admin', 'supervisor'])) {
            // Unauthorized access
            return ResponseHelper::authorizationFail();
        }

        // Delete the form
        $form->delete();

        // Return success response
        return ResponseHelper::success();
    }

    public function getFormUser()
    {
        // Retrieve the user's email address
        $email = Auth::user()->email;

        // Query the DesignedForm model to find forms where the user's email is listed in the recipients field
        $forms = DesignedForm::whereJsonContains('recipients', $email)->get();

        // Return success response with the forms data
        return ResponseHelper::success(DesignedFormResource::collection($forms));
    }


    public function getSurvey($slug, Request $request)
    {
        // Validate the slug parameter
        $validatedSlug = Validator::make(['slug' => $slug], [
            'slug' => 'required|string|exists:designed_forms,slug',
        ])->validated()['slug'];

        $form = DesignedForm::with('questions')
            ->where('slug', $validatedSlug)
            ->firstOrFail();

         $surveyUser = null;
        if ($request->has('user')) {
            try {
                $encryptedUser = urldecode($request->query('user'));
                $userId = Crypt::decrypt($encryptedUser); // يفك التشفير
                $surveyUser = User::findOrFail($userId);
                Log::info('Encrypted user param:', [$request->query('user')]);

            } catch (\Throwable $e) {
                Log::error("Survey decrypt error: " . $e->getMessage());
                $surveyUser = null;
            }
        }
        $categories = Category::all();
        $cities = City::all();
        $url = env('WEBSITE_URL');
        $companies = Company::all();
        // $categories ,$cities ,$title = 'LPCentre';
        // $description = null;
        // $keywords = null;
        return view('new.survey', [
            'form' => $form,
            'questions' => $form->questions,
            'categories' => $categories,
            'cities' => $cities,
            'website_url' => $url,
            'user' => $surveyUser ?? auth()->user(),
            'companies' => $companies
        ]);
    }

    public function SubmitSurvey(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'nullable',
            'form_id' => 'required|exists:designed_forms,id',
            'email' => 'sometimes|required|email',
        ]);
        DB::beginTransaction();

        try {
            // Check if the user is authenticated
            $userId = $request->input('user_id');
            if ($userId) {
                if (
                    AnswerdForm::where('user_id', $userId)
                        ->where('designed_form_id', $request->input(key: 'form_id'))
                        ->where('answered', true)
                        ->exists()
                ) {
                    return ResponseHelper::AlreadyExists();
                }

                foreach ($request->answers as $answerData) {
                    Answer::create([
                        'lang_code' => $request->header('lang') ?? 'en',
                        'answer' => json_encode($answerData['answer']),
                        'user_id' => $userId,
                        'question_id' => $answerData['question_id'],
                    ]);
                }

                //create AnswerdForm
                // AnswerdForm::create([
                //     'designed_form_id'=>$request->input(key: 'form_id'),
                //     'user_id' =>$userId,
                //     'answered' => true
                // ]);
                AnswerdForm::updateOrCreate(
                    [
                        'designed_form_id' => $request->input('form_id'),
                        'user_id' => $userId
                    ],
                    [
                        'answered' => true
                    ]
                );

                // Update recipient's pivot table for the authenticated user
                // $form = DesignedForm::findOrFail($request->input(key: 'form_id'));
                // $form->recipients()->updateExistingPivot(Auth::id(), ['answered' => true]);
            } else {
                if (
                    Answer::whereHas('guestSurvey', function ($query) use ($request) {
                        $query->where('email', $request->input('email'))
                            ->where('designed_form_id', $request->form_id);
                    })->exists()
                ) {
                    return ResponseHelper::AlreadyExists();
                }
                $guestSurvey = GuestSurvey::create([
                    'email' => $request->input('email'),
                    'designed_form_id' => $request->input('form_id'),
                    'info' => json_encode($request->input('info', null)),
                ]);

                // Store each answer individually for the guest user
                foreach ($request->answers as $answerData) {
                    Answer::create([
                        'answer' => json_encode($answerData['answer']),
                        'guest_survey_id' => $guestSurvey->id,
                        'question_id' => $answerData['question_id'],
                    ]);
                }
            }

            DB::commit();
            return ResponseHelper::success();

        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::DataNotFound();
        }
    }

    // public function getSurveyPdf($slug)
    // {
    //     // Validate the slug input
    //     $validatedSlug = Validator::make(['slug' => $slug], [
    //         'slug' => 'required|string|exists:designed_forms,slug',
    //     ])->validated()['slug'];

    //     // Fetch the form along with questions
    //     $form = DesignedForm::with('questions')
    //         ->where('slug', $validatedSlug)
    //         ->firstOrFail();

    //     // Fetch additional data for the view
    //     $categories = Category::all();
    //     $cities = City::all();
    //     $url = env('WEBSITE_URL');
    //     $companies = Company::all();

    //     try {
    //         // تحديد الاتجاه بناءً على النصوص
    //         $direction = 'ltr';
    //         foreach ($form->questions as $question) {
    //             if (preg_match('/\p{Arabic}/u', $question->title) || preg_match('/\p{Arabic}/u', $question->question)) {
    //                 $direction = 'rtl';
    //                 break;
    //             }
    //         }
    //         // Generate the PDF from the view
    //         $pdf = Pdf::loadView('new.survey-pdf', [
    //             'form' => $form,
    //             'questions' => $form->questions,
    //             'categories' => $categories,
    //             'cities' => $cities,
    //             'website_url' => $url,
    //             'user' => auth()->user(),
    //             'companies' => $companies,
    //             'direction' => $direction,
    //         ])->setOption('defaultFont', 'Amiri')
    //           ->setOption('encoding', 'UTF-8')
    //           ->setOption('isHtml5ParserEnabled', true)
    //           ->setOption('enable_font_subsetting', true);

    //         // Define the file name and file path
    //         $fileName = 'survey_' . $form->slug . '.pdf';
    //         $filePath = public_path('survey/' . $fileName);

    //         // Ensure the directory exists
    //         if (!file_exists(public_path('survey'))) {
    //             if (!mkdir(public_path('survey'), 0777, true) && !is_dir(public_path('survey'))) {
    //                 return ResponseHelper::operationFail('Directory creation failed');
    //             }
    //         }

    //         // Save the PDF file to the public directory
    //         file_put_contents($filePath, $pdf->output());

    //         // Return the shareable link to the PDF
    //         $shareableLink = url('survey/' . $fileName);

    //         return response()->json([
    //             'link' => $shareableLink,
    //         ]);

    //     } catch (\Exception $e) {
    //         // Log the error and return an error response
    //         Log::error('PDF generation failed: ' . $e->getMessage());

    //         return ResponseHelper::operationFail('Failed to generate PDF');

    //     }
    // }

    public function previewSurveyPdf($slug)
{
    // Validate slug
    $validatedSlug = Validator::make(['slug' => $slug], [
        'slug' => 'required|string|exists:designed_forms,slug',
    ])->validated()['slug'];

    // Fetch data
    $form = DesignedForm::with('questions')
        ->where('slug', $validatedSlug)
        ->firstOrFail();

    $categories = Category::all();
    $cities = City::all();
    $url = env('WEBSITE_URL');
    $companies = Company::all();

    // تحديد الاتجاه (RTL/LTR)
    $direction = 'ltr';
    foreach ($form->questions as $question) {
        if (preg_match('/\p{Arabic}/u', $question->title) || preg_match('/\p{Arabic}/u', $question->question)) {
            $direction = 'rtl';
            break;
        }
    }

    return view('new.survey-pdf', [
        'form' => $form,
        'questions' => $form->questions,
        'categories' => $categories,
        'cities' => $cities,
        'website_url' => $url,
        'user' => auth()->user(),
        'companies' => $companies,
        'direction' => $direction,
    ]);
}


        public function getSurveyPdf($slug)
        {
            // Validate the slug input
            $validatedSlug = Validator::make(['slug' => $slug], [
                'slug' => 'required|string|exists:designed_forms,slug',
            ])->validated()['slug'];

            // Fetch the form along with questions
            $form = DesignedForm::with('questions')
                ->where('slug', $validatedSlug)
                ->firstOrFail();

            // Fetch additional data for the view
            $categories = Category::all();
            $cities = City::all();
            $url = env('WEBSITE_URL');
            $companies = Company::all();

            try {
                // تحديد الاتجاه بناءً على النصوص
                $direction = 'ltr';
                foreach ($form->questions as $question) {
                    if (preg_match('/\p{Arabic}/u', $question->title) || preg_match('/\p{Arabic}/u', $question->question)) {
                        $direction = 'rtl';
                        break;
                    }
                }

                $fileName = 'survey_' . $form->slug . '_' . time() . '.pdf';

                $filePath = public_path('survey/' . $fileName);

                if (!file_exists(public_path('survey'))) {
                    if (!mkdir(public_path('survey'), 0777, true) && !is_dir(public_path('survey'))) {
                        return ResponseHelper::operationFail('Directory creation failed');
                    }
                }

                $footer_contact1 = FooterSetting::where('name', 'footer_contact1')->first();
                $contact_message1 = $footer_contact1 ? $footer_contact1->value : '';

                $footer_contact2 = FooterSetting::where('name', 'footer_contact2')->first();
                $contact_message2 = $footer_contact2 ? $footer_contact2->value : '';

                $footer_contact3 = FooterSetting::where('name', 'footer_contact3')->first();
                $contact_message3 = $footer_contact3 ? $footer_contact3->value : '';

                $footer_contact4 = FooterSetting::where('name', 'footer_contact4')->first();
                $contact_message4 = $footer_contact4 ? $footer_contact4->value : '';

                $footer_categories = FooterSetting::where('name', 'footer_categories')->first();
                $footer_cities = FooterSetting::where('name', 'footer_cities')->first();

                $footer_categories_arr = explode(',', $footer_categories ? $footer_categories->value : '');
                $footer_cities_arr = explode(',', $footer_cities ? $footer_cities->value : '');

                $_footer_categories = Category::whereIn('id', $footer_categories_arr)->get();
                $_footer_cities = City::whereIn('id', $footer_cities_arr)->get();

                $footer_whatsapp = FooterSetting::where('name', 'footer_whatsapp')->first();
                $_footer_whatsapp = $footer_whatsapp ? $footer_whatsapp->value : '';

                $footer_facebook = FooterSetting::where('name', 'footer_facebook')->first();
                $_footer_facebook = $footer_facebook ? $footer_facebook->value : '';

                $footer_twitter = FooterSetting::where('name', 'footer_twitter')->first();
                $_footer_twitter = $footer_twitter ? $footer_twitter->value : '';

                $footer_linkedin = FooterSetting::where('name', 'footer_linkedin')->first();
                $_footer_linkedin = $footer_linkedin ? $footer_linkedin->value : '';

                $pdf = Pdf::view('new.survey-pdf', [
                            'form' => $form,
                            'questions' => $form->questions,
                            'categories' => $categories,
                            'cities' => $cities,
                            'website_url' => $url,
                            'user' => auth()->user(),
                            'companies' => $companies,
                            'direction' => $direction,
                        ])
                        ->withBrowsershot(function (Browsershot $browsershot) use (
                             $form
                            ) {
                            File::ensureDirectoryExists(storage_path('app/browsershot'));

                            $chromePath = env('BROWSERSHOT_CHROME_PATH');

                            $browsershot
                                ->setChromePath($chromePath) // مسار Chrome المخصص
                                ->setNodeBinary('/usr/local/bin/run-browsershot-as-root-lpccrm') // سكريبت الـ Wrapper
                                ->addChromiumArguments([
                                    '--no-sandbox',
                                    '--disable-setuid-sandbox',
                                    '--disable-dev-shm-usage',
                                    '--disable-gpu',
                                    '--user-data-dir=' . storage_path('app/browsershot'),
                                ])
                                ->margins(50, 0, 0, 0);

                        })
                        ->format('A4')
                        ->save($filePath);


                        $shareableLink = url('survey/' . $fileName);

                        return response()->json([
                            'link' => $shareableLink,
                        ]);

                    } catch (\Exception $e) {
                        Log::error('PDF generation failed: ' . $e->getMessage());

                        return ResponseHelper::operationFail('Failed to generate PDF');
                    }
    }



    //add rate to answered survey
    public function addSurveyRate(SurveyRateRequest $request)
    {
        $form = AnswerdForm::where('designed_form_id', $request->designed_form_id)
        ->where('user_id', $request->user_id)
        ->first();

        if (!$form) {
            return ResponseHelper::DataNotFound('Answered form not found for this user');
        }

        $form->update([
            'rate'=>$request->rate,
            'message'=>$request->input('message')
        ]);

        return ResponseHelper::success('Rate added successfully');
    }



}
