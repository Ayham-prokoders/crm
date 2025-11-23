<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyBuilder;
use App\Http\Helper\ResponseHelper;
use App\Models\SurveyBuilderAnswer;
use App\Models\SurveyBuilderRecipient;

class SurveyBuilderAnswerController extends Controller
{
    public function submitAnswers(Request $request, $slug)
    {
        $survey = SurveyBuilder::where('slug', $slug)->firstOrFail();

        $token = $request->input('token');

        if ($token) {
                $recipient = SurveyBuilderRecipient::where('survey_builder_id', $survey->id)
                    ->where('hmac_token', $token)
                    ->first();

                if (!$recipient) {
                    return ResponseHelper::invalidData('Invalid or expired token');
                }

                $userId = $recipient->user_id;

                SurveyBuilderAnswer::create([
                    'survey_id' => $survey->id,
                    'user_id' => $userId,
                    'answers' => $request->input('answers'),
                ]);

                $recipient->update([
                    'answered_at' => now(),
                ]);
        } else {
                SurveyBuilderAnswer::create([
                    'survey_id' => $survey->id,
                    'user_id'   => null,
                    'answers'   => $request->input('answers'),
                ]);
            }

        return ResponseHelper::success('Answers submitted successfully.');
    }


    public function getAnswers($surveyId)
    {
        $survey = SurveyBuilder::with(['answers.user'])->findOrFail($surveyId);

        $answers = $survey->answers->map(function ($answer) {
            return [
                'user' => $answer->user ? [
                    'id' => $answer->user->id,
                    'name' => $answer->user->name,
                    'email' => $answer->user->email,
                ] : null,
                'submitted_at' => $answer->created_at->toDateTimeString(),
                'answers' => $answer->answers,
            ];
        });

       return ResponseHelper::success([ 'answers' => $answers ]);

    }

}
