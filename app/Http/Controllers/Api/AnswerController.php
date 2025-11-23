<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\{Answer,DesignedForm};
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\AnswerResource;
class AnswerController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Answer::class)
            ->allowedFilters(['answer','question_id'])
            ->allowedSorts(['answer', 'created_at']);

            $answers = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();
            $data = [
                'answers' => AnswerResource::collection($answers),
            ];
            if ($request->input('limit')) {
                $data['pagination'] = [
                    'total' => $answers->total(),
                    'per_page' => $answers->perPage(),
                    'current_page' => $answers->currentPage(),
                    'last_page' => $answers->lastPage(),
                    'from' => $answers->firstItem(),
                    'to' => $answers->lastItem(),
                    'links' => [
                        'first' => $answers->url(1),
                        'last' => $answers->url($answers->lastPage()),
                        'prev' => $answers->previousPageUrl(),
                        'next' => $answers->nextPageUrl(),
                    ],
                ];
            }
        // Return success response with data
        return ResponseHelper::success($data);
    }



    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'nullable',
            'form_id' => 'nullable|exists:designed_forms,id',
        ]);

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Store each answer individually
            foreach ($request->answers as $answerData) {
                Answer::create([
                    'lang_code'=>$request->header('lang') ?? 'en',
                    'answer' => json_encode($answerData['answer']),
                    'user_id' => Auth::id(),
                    'question_id' => $answerData['question_id'],
                ]);
            }

            // Commit the transaction
            DB::commit();
            $form = DesignedForm::findOrFail($request->input('form_id'));
            $form->recipients()->updateExistingPivot(Auth::id(), ['answered' => true]);
            // Return success response
            return ResponseHelper::success();
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Return error response
            return ResponseHelper::DataNotFound();
        }
    }
    public function destroy(Answer $answer)
    {

        // Delete the answer
        $answer->delete();

        // Return success response
        return ResponseHelper::success();
    }
}
