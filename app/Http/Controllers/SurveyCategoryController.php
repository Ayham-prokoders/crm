<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyCategory;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\SurveyCategory\SurveyCategoryRequest;

class SurveyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SurveyCategory::select('id','name','created_at')
            ->when($request->input('search'), function ($q, $search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        $categories = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();
        return ResponseHelper::success($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurveyCategoryRequest $request)
    {
        $surveyCategory = SurveyCategory::create($request->validated());
        return ResponseHelper::success($surveyCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyCategory $surveyCategory)
    {
        return ResponseHelper::success($surveyCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurveyCategoryRequest $request, SurveyCategory $surveyCategory)
    {
        $surveyCategory->update($request->validated());
        return ResponseHelper::success($surveyCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyCategory $surveyCategory)
    {
        $surveyCategory->delete();
        return ResponseHelper::success();
    }
}
