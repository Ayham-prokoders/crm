<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\QuestionResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\QuestionRequest;
use App\Models\Role;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Question::class)
            ->allowedFilters(['type','designed_form_id','question'])
            ->allowedSorts(['question', 'created_at']);

            $questions = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();
            $data = [
                'questions' => QuestionResource::collection($questions),

            ];
            if ($request->input('limit')) {
                $data['pagination'] = [
                    'total' => $questions->total(),
                    'per_page' => $questions->perPage(),
                    'current_page' => $questions->currentPage(),
                    'last_page' => $questions->lastPage(),
                    'from' => $questions->firstItem(),
                    'to' => $questions->lastItem(),
                    'links' => [
                        'first' => $questions->url(1),
                        'last' => $questions->url($questions->lastPage()),
                        'prev' => $questions->previousPageUrl(),
                        'next' => $questions->nextPageUrl(),
                    ],
                ];
            }
        // Return success response with data
        return ResponseHelper::success($data);
    }

    public function store(QuestionRequest $request)
    {
        // Check if the user has permission to create a question
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }


        $options = $request->has('options') ? json_encode($request->input('options')) :null;

        // Create question
            $question = Question::create([
                'lang_code'=>$request->header('lang') ?? 'en',
                'type' => $request->input('type'),
                'question' => $request->input('question'),
                'is_required'=>$request->input('is_required'),
                'options' => $options,
                'designed_form_id' => $request->input('designed_form_id'),
                    ]);

        return ResponseHelper::create(new QuestionResource($question));
    }

    public function update(QuestionRequest $request, Question $question)
    {
        // Check if the user has permission to update a question
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }


        $options = $request->has('options') ? json_encode($request->input('options')) :null;

        // Update the question
        $question->update([
            'lang_code'=>$request->header('lang') ?? 'en',
            'type' => $request->input('type'),
            'question' => $request->input('question'),
            'options' => $options,
            'is_required'=>$request->input('is_required'),
            'designed_form_id' => $request->input('designed_form_id'),

        ]);

        // Return the updated question
        return ResponseHelper::success(new QuestionResource($question));
    }

    public function destroy(Question $question)
    {
        // Check if the user has permission to delete a question
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);

       if (!in_array($role->name, ['admin', 'supervisor','trainer'])) {
           return ResponseHelper::authorizationFail();
       }

        // Delete the question
        $question->delete();

        // Return success response
        return ResponseHelper::success();
    }
}
