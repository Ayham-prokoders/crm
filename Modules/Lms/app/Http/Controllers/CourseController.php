<?php

namespace Modules\Lms\Http\Controllers;

use App\Jobs\UpdateCourseCodesJob;
use App\Services\CourseMatchService;
use App\Services\GenerateCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Lms\Models\{Course, Category};
use App\Http\Helper\{CourseHelper, ResponseHelper};
use App\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Modules\Lms\Http\Resources\{CourseResource, CategoryResource};
use Modules\Lms\Http\Requests\CategoryCodeRequest;
class CourseController extends Controller
{
    protected $course;

    public function __construct()
    {
        $this->course = new CourseHelper();
    }

    public function index(Request $request)
    {
        $courses = $this->course->getCategories();
        $courses = $this->course->getCourses();

        // return $courses;
        return ResponseHelper::success(CourseResource::collection($courses));

    }

    public function list_categories(Request $request)
    {

        $query = QueryBuilder::for(Category::class)
            ->allowedFilters(['type', 'title'])
            ->allowedSorts(['type', 'title']);

        $categories = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'categories' => CategoryResource::collection($categories),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
                'links' => [
                    'first' => $categories->url(1),
                    'last' => $categories->url($categories->lastPage()),
                    'prev' => $categories->previousPageUrl(),
                    'next' => $categories->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);


        $query = QueryBuilder::for(Course::class)
            ->allowedFilters(['type', 'category_id', 'title', 'online'])
            ->allowedSorts(['type', 'title']);


        if ($request->has('city')) {
            $query->whereHas('schedules', function ($q) use ($request) {
                $q->where('city_id', $request->input('city'));
            });
        }

        if ($role->name === 'trainee') {
            $query->whereHas('classes.trainees', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($role->name === 'trainer') {
            $query->whereHas('classes', function ($q) use ($user) {
                $q->where('trainer_id', $user->id);
            });
        } elseif ($role->name === 'companySupervisor') {
            $query->whereHas('classes.trainees', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            });
        } elseif (!in_array($role->name, ['admin', 'supervisor', 'accreditation_manager', 'viewer', 'accountant', 'coordinator'])) {
            return ResponseHelper::authorizationFail();
        }

        $courses = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'total_in_city' => $request->has('city') ? $query->count() : null,
            'courses' => CourseResource::collection($courses),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'from' => $courses->firstItem(),
                'to' => $courses->lastItem(),
                'links' => [
                    'first' => $courses->url(1),
                    'last' => $courses->url($courses->lastPage()),
                    'prev' => $courses->previousPageUrl(),
                    'next' => $courses->nextPageUrl(),
                ],
            ];
        }


        return ResponseHelper::success($data);
    }

    public function getCourses(Request $request)
    {

        $query = QueryBuilder::for(Course::class)
            ->allowedFilters([
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('online'),
        ]);

        $courses = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'courses' => CourseResource::collection($courses),
        ];

        if ($request->input('limit')) {
            $data['pagination'] = [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'from' => $courses->firstItem(),
                'to' => $courses->lastItem(),
                'links' => [
                    'first' => $courses->url(1),
                    'last' => $courses->url($courses->lastPage()),
                    'prev' => $courses->previousPageUrl(),
                    'next' => $courses->nextPageUrl(),
                ],
            ];
        }

        return ResponseHelper::success($data);
    }


    public function getRelatedCourse(Request $request)
    {

        $course = Course::findOrFail($request->input('course_id'));
        $relatedCourses = json_decode($course->related_courses, true);

        $rels = [];
        foreach ($relatedCourses as $relatedCourseId) {
            $relatedCourse = Course::find($relatedCourseId);
            if ($relatedCourse) {
                $rels[] = new CourseResource($relatedCourse);
            }
        }

        return ResponseHelper::success($rels);
    }

    public function getCourseTrainers($courseId)
    {
        $course = Course::with('trainers')->find($courseId);

        if (!$course) {
            return ResponseHelper::DataNotFound();
        }

        return ResponseHelper::success($course->trainers);
    }


    public function getCourseAverageRating(Request $request, $courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return ResponseHelper::DataNotFound();
        }
        $feedbacks = $course->feedbacks()->get();
        if ($feedbacks->isEmpty()) {
            return ResponseHelper::success([
                'averages' => [
                    'rate' => null,
                    'trainers_rate' => null,
                    'materials_rate' => null,
                    'hospitality_rate' => null,
                    'hotel_rate' => null,
                ],
            ]);
        }
        $fields = ['rate', 'trainers_rate', 'materials_rate', 'hospitality_rate', 'hotel_rate'];
        $averages = [];
        foreach ($fields as $field) {
            $sum = $feedbacks->whereNotNull($field)->sum($field);
            $count = $feedbacks->whereNotNull($field)->count();
            $averages[$field] = $count > 0 ? $sum / $count : null;
        }

        return ResponseHelper::success([
            'averages' => $averages,
        ]);
    }



    public function updateCourseCode(CategoryCodeRequest $request): bool
    {
        \Log::info('from update');

        $externalId = $request->category_id;
        $newCode = strtoupper($request->new_code);
        $projectSource = $request->project_source;

        $category = Category::withoutGlobalScope('project_source_l1')
            ->where('external_id', $externalId)
            ->where('project_source', $projectSource)
            ->first();

        if (!$category) {
            return false;
        }

        $oldCode = $category->code;

        // Update the category code
        $category->code = $newCode;
        $category->save();

        // Get all related courses where the code starts with OLD_CODE + projectSource
        $oldPrefix = strtoupper($oldCode . $projectSource);
        $newPrefix = strtoupper($newCode . $projectSource);

        // Dispatch job
        dispatch(new UpdateCourseCodesJob($oldPrefix, $newPrefix, $newCode));


        return true;
    }
    public function createCourseFromCMS(Request $request)
    {
        $category = Category::where('external_id', $request->category_id)
            ->where('project_source', $request->project_source)
            ->first();
        if (!$category) {
            return ResponseHelper::notFound('Category not found');
        }

        $course = Course::updateOrCreate(
            [
                'external_id' => $request->id,
                'project_source' => $request->project_source
            ],
            [
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
                'days_content' => $request->days_content,
                'related_courses' => $request->related_courses,
                'category_id' => $category->id,
                'online' => $request->online,
            ]
        );
        if (!$category->code) {
            return ResponseHelper::notFound('no code for Category');
        }
        $newCode = app()->make(GenerateCodeService::class)->generateCourseCode($course, $category);
        $course->update([
            'code' => $newCode,
            'base_code' => $newCode,
        ]);
        return response()->json([
            'code' => $newCode
        ], 200);
    }

    /**
     * all categories by project source
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request){
        $source = $request->query('source');
        $categories = Category::withoutGlobalScope('project_source_l1')
            ->when($source, function ($query) use ($source) {
                return $query->byProjectSource($source);
            })->get();
        return response()->json($categories);
    }

}
