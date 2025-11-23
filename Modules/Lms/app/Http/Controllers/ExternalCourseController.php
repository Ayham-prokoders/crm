<?php

namespace Modules\Lms\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Modules\Lms\Models\ExternalCourse;
use Modules\Lms\Http\Requests\ExternalCourseRequest;
use Modules\Lms\Transformers\ExternalCourseResource;
use Spatie\QueryBuilder\AllowedFilter;


class ExternalCourseController extends Controller
{
    public function store(ExternalCourseRequest $request)
    {
        $data = $request->validated();
        $externalCourse = ExternalCourse::create($data);

        return ResponseHelper::success([
            'course' => new ExternalCourseResource($externalCourse),
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $currentRole = $user->current_role_id;
        $role = Role::find($currentRole);


        $query = QueryBuilder::for(ExternalCourse::class)
            ->allowedFilters([ 'category_id', 'name', 'online','code'])
            ->allowedSorts([ 'name']);


        if ($request->has('city')) {
            $query->whereHas('schedules', function ($q) use ($request) {
                $q->where('city_id', $request->input('city'));
            });
        }

        $courses = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'total_in_city' => $request->has('city') ? $query->count() : null,
            'courses' => ExternalCourseResource::collection($courses),
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

    public function getExternalCourses(Request $request)
    {
        $query = QueryBuilder::for(ExternalCourse::class)
            ->allowedFilters([
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('online'),
        ]);

        $courses = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();

        $data = [
            'courses' => ExternalCourseResource::collection($courses),
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

    public function show(ExternalCourse $externalCourse)
    {
        return ResponseHelper::success([
            'course' => new ExternalCourseResource($externalCourse),
        ]);
    }

    public function update(ExternalCourseRequest $request, ExternalCourse $externalCourse)
    {
        $data = $request->validated();
        $externalCourse->update($data);

        return ResponseHelper::success([
            'course' => new ExternalCourseResource($externalCourse),
        ]);
    }
}
