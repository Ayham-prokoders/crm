<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Lms\Models\Course;
use App\Models\CourseCodeMatch;
use Modules\Lms\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\MachingRequst;
use App\Services\CourseMatchService;
use Illuminate\Support\Facades\Auth;
use App\Jobs\DeleteMatchedSchedulesJob;
use App\Http\Requests\GetMatchesRequest;
use App\Http\Requests\GetTagrtCourseRequest;

class CourseMatchingController extends Controller
{
    public function __construct(protected CourseMatchService $courseMatchService)
    {
    }

    /**
     * match and sunc logic
     * @param \App\Http\Requests\MachingRequst $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function match(MachingRequst $request)
    {
        $data = $request->validated();
        $sourceCourse = Course::findOrFail($data['source_course_id']);
        $targetCourse = Course::findOrFail($data['target_course_id']);
        try {
            $this->courseMatchService->matchAndSyncCode(
                $sourceCourse,
                $targetCourse,
                $data['target_site'],
            );
            $targetCourse->load('codeMatches.sourceCourse');
            return response()->json([
                'status' => 'success',
                'message' => 'Course code matched and synced successfully.',
                'data' => [
                    'target_course' => [
                        'id' => $targetCourse->id,
                        'title' => $targetCourse->name,
                        'project_source' => $targetCourse->project_source,
                        'current_code' => $targetCourse->current_matched_code,
                        'code_history' => $targetCourse->code_history,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * get the categories list
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function get_categories_by_source(Request $request)
    {
        $categories = Category::select(['id', 'project_source', 'type', 'link_id', 'code', 'external_id'])
            ->where('project_source', $request->query('site_key'))
            ->withoutGlobalScope('project_source_l1')
            ->get();

        return response()->json([
            'data' => $categories,
            'message' => __('categories by source'),
            'status' => 200,
        ]);
    }

    /**
     * get the course
     * @param \App\Http\Requests\GetTagrtCourseRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function get_courses(GetTagrtCourseRequest $request)
    {

        $data = $request->validated();
        $query = Course::with([
            'codeMatches.sourceCourse' => function ($q) {
                $q->select('id', 'name');
            },
            'codeMatches.targetCourse' => function ($q) {
                $q->select('id', 'name');
            },
        ])
            ->where('category_id', $data['category_id'])
            ->where('online', $data['type'])
            ->where('project_source', $data['site_key']);

        if ($data['site_key'] === 'L1' && isset($data['course_type'])) {
            $query->where('course_type', $data['course_type']);
        }

        $courses = $query->get();

        return response()->json([
            'data' => $courses,
            'message' => __('course returbed successfully'),
            'status' => 200,
        ]);
    }

    /**
     * get matches table
     * @param \App\Http\Requests\GetMatchesRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getMatches(GetMatchesRequest $request)
    {
        $data = $this->courseMatchService->getMatches($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function undoMatching($courseId)
    {
        $targetCourse = Course::withoutGlobalScopes()->findOrFail($courseId);

        return DB::transaction(function () use ($targetCourse) {
            $userId = Auth::id();
            logger('Target course ID:', [$targetCourse->id]);
            $activeMatch = CourseCodeMatch::where('target_course_id', $targetCourse->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$activeMatch) {
                throw new \Exception("No active match found to undo.");
            }

            $activeMatch->update(['status' => 'not_active']);

            $previousMatch = CourseCodeMatch::where('target_course_id', $targetCourse->id)
                ->where('status', 'not_active')
                ->where('id', '!=', $activeMatch->id)
                ->latest()
                ->first();

            if (!$previousMatch) {
                throw new \Exception("No previous code found for rollback.");
            }

            $source_course_id = $activeMatch->source_course_id;
            logger('source course ID:', [$source_course_id]);
            $previousMatch->update(['status' => 'active']);
            $targetCourse->update([
                'code' => $previousMatch->applied_code,
            ]);

            $site = $this->getSiteByProjectName($previousMatch->target_project);
            if (!$site) {
                throw new \Exception("Site configuration for '{$previousMatch->target_project}' not found.");
            }
            
            dispatch(new DeleteMatchedSchedulesJob($targetCourse->id, $activeMatch->id, $site))
                ->delay(now()->addSeconds(2))
                ->afterCommit();

            $this->courseMatchService->syncCodeToTargetSite($site, $targetCourse->external_id, $previousMatch->applied_code);
            $this->courseMatchService->log(
                'undo',
                $userId,
                $source_course_id,
                $targetCourse->id,
                "Undid match for target course '{$targetCourse->name}'"
            );

            $targetCourse->load('codeMatches.sourceCourse');

            return response()->json([
                'status' => 'success',
                'message' => 'Course code rollback completed successfully.',
                'data' => [
                    'target_course' => [
                        'id' => $targetCourse->id,
                        'title' => $targetCourse->name,
                        'project_source' => $targetCourse->project_source,
                        'current_code' => $targetCourse->current_matched_code,
                        'code_history' => $targetCourse->code_history,
                    ],
                ],
            ]);
        });
    }


    public function getSiteByProjectName($projectName)
    {
        $sites = config('services.sites');

        foreach ($sites as $site) {
            if (isset($site['project']) && $site['project'] === $projectName) {
                return $site;
            }
        }

        return null;
    }

}
