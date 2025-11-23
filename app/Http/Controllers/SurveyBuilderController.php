<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FooterSetting;
use App\Models\SurveyBuilder;
use Modules\Lms\Models\Location;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\SurveyBuilderRecipient;
use Spatie\QueryBuilder\AllowedFilter;
use App\Mail\SurveyBuilderInvitationMail;
use App\Http\Requests\SurveyBuilderRequest;

class SurveyBuilderController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(SurveyBuilder::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('survey_category_id'),
            ])
            ->allowedSorts(['name', 'created_at'])
            ->with('surveyCategory:id,name');

        $surveys = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        return ResponseHelper::success($surveys);
    }

    public function store(SurveyBuilderRequest $request)
    {
        $survey = SurveyBuilder::create($request->validated());
        return ResponseHelper::create($survey);
    }

    public function show(SurveyBuilder $survey)
    {
        return ResponseHelper::success($survey);
    }

    public function update(SurveyBuilderRequest $request, SurveyBuilder $survey)
    {
        $survey->update($request->validated());
        return ResponseHelper::success($survey);
    }

    public function destroy(SurveyBuilder $survey)
    {
        return ResponseHelper::success($survey->delete());
    }

    public function showBySlug($slug)
    {
        $survey = SurveyBuilder::where('slug', $slug)->firstOrFail();
        return ResponseHelper::success($survey);
    }

    public function sendSurvey(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:survey_builders,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $survey = SurveyBuilder::findOrFail($request->survey_id);

        if (!$survey->slug) {
            $survey->slug = Str::slug($survey->name . '-' . Str::random(6));
            $survey->save();
        }

        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);
            $payload = $survey->id . '|' . $userId;
            $secret = config('services.survey.hmac_secret');
            $token = hash_hmac('sha256', $payload, $secret);

            SurveyBuilderRecipient::updateOrCreate(
                    [
                        'survey_builder_id' => $survey->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'hmac_token' => $token,
                    ]
                );

            $link = env('PROJECT_FRONTEND') . '/survey/' . $survey->slug . '?token=' . $token;
            Mail::to($user->email)->queue(new SurveyBuilderInvitationMail($user, $survey, $link));
        }

        return ResponseHelper::success('Survey invitations sent successfully.');
    }

    public function getFooterData()
    {
        $settings = FooterSetting::pluck('value', 'name')->toArray();

        $social = [
            'whatsapp' => $settings['footer_whatsapp'] ?? '',
            'facebook' => $settings['footer_facebook'] ?? '',
            'twitter' => $settings['footer_twitter'] ?? '',
            'linkedin' => $settings['footer_linkedin'] ?? '',
            'youtube' => $settings['footer_youtube'] ?? '',
        ];
        return response()->json([
            'social' => $social,
        ]);

    }



}

