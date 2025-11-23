<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{GuestSurvey,Answer};
use App\Models\GuestSurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Helper\ResponseHelper;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class GuestSurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Fetch all Guest Surveys with pagination
       $guestSurveys = GuestSurvey::with(['designedForm'])
       ->paginate(10);
       return ResponseHelper::success($guestSurveys);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'info' => 'nullable|array',
            'form_id' => 'required|exists:designed_forms,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'nullable',
        ]);

        // // Begin a database transaction
        // DB::beginTransaction();

        // try {
            // Create the guest survey
            $guestSurvey = GuestSurvey::create([
                'email' => $request->input('email'),
                'designed_form_id' => $request->input('form_id'),
                'info' => json_encode($request->input('info', null)),
            ]);

            // Store each answer individually
            // foreach ($request->answers as $answerData) {
            //     GuestSurveyAnswer::create([
            //         'answer' => json_encode($answerData['answer']),
            //         'question_id' => $answerData['question_id'],
            //         'guest_survey_id' => $guestSurvey->id,
            //     ]);
            // }
            foreach ($request->answers as $answerData) {
                Answer::create([
                    'answer' => json_encode($answerData['answer']),
                    'guest_survey_id' => $guestSurvey->id,
                    'question_id' => $answerData['question_id'],
                ]);
            }

            // Commit the transaction
            // DB::commit();

            // Return success response
            return ResponseHelper::create();
        // } catch (\Exception $e) {
        //     // Rollback the transaction in case of any error
        //     DB::rollBack();

        //     // Return error response
        //     return response()->json(['error' => 'An error occurred while submitting the survey.'], 500);
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
