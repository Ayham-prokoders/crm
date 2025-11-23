<?php

namespace Modules\DealManagement\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Lms\Models\{Course,ExternalCourse};
use Illuminate\Support\Facades\DB;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Modules\DealManagement\Models\Deal;
use Spatie\QueryBuilder\Filters\FilterExact;
use Modules\DealManagement\Enums\CurrencyEnum;
use Modules\DealManagement\Http\Requests\DealRequest;
use Modules\DealManagement\Transformers\DealResource;
use Modules\DealManagement\Transformers\DealCollection;

class DealController extends Controller
{
    public function store(DealRequest $request)
    {
        $validated = $request->validated();
        $courseType = $request->input('course_type');
        $courseId = $validated['course_id'];

        $course = $courseType === 'custom'
        ? ExternalCourse::find($courseId)
        : Course::find($courseId);

        $validated['name'] = $course->name . ' - ' . $course->duration;
        $deal = Deal::create($validated);

        if ($request->type === 'company' && $request->has('trainees')) {
            $deal->users()->sync($request->trainees);
        }

        return ResponseHelper::create(new DealResource($deal));
    }

    public function show(Deal $deal)
    {
        $deal->load(['course','externalCourse' ,'classe', 'company', 'user', 'users']);

        return new DealResource($deal);
    }

    public function index(Request $request)
    {
        $deals = QueryBuilder::for(Deal::class)
        ->with(['course','externalCourse', 'classe', 'company', 'user', 'users'])
            // ->allowedFilters(['type', 'user_id','course_id','classe_id','company_id'])
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('course_id'),
                AllowedFilter::exact('course_type'),
                AllowedFilter::exact('classe_id'),
                AllowedFilter::exact('company_id'),
                AllowedFilter::callback('schedule_id', function ($query, $value) {
                    $query->whereHas('classe', function ($q) use ($value) {
                        $q->where(function ($subQ) use ($value) {
                            $subQ->where('course_type', 'official')
                                ->whereHas('schedule', function ($scheduleQ) use ($value) {
                                    $scheduleQ->where('id', $value);
                                });
                        })->orWhere(function ($subQ) use ($value) {
                            $subQ->where('course_type', 'custom')
                                ->whereHas('externalSchedule', function ($scheduleQ) use ($value) {
                                    $scheduleQ->where('id', $value);
                                });
                        });
                    });
                }),
                AllowedFilter::callback('city', function ($query, $value) {
                    $query->whereHas('classe', function ($q) use ($value) {
                        $q->where(function ($subQ) use ($value) {
                            $subQ->where('course_type', 'official')
                                ->whereHas('schedule.city', function ($cityQ) use ($value) {
                                    $cityQ->where('id', $value);
                                });
                        })->orWhere(function ($subQ) use ($value) {
                            $subQ->where('course_type', 'custom')
                                ->whereHas('externalSchedule.city', function ($cityQ) use ($value) {
                                    $cityQ->where('id', $value);
                                });
                        });
                    });
                }),

                AllowedFilter::callback('year', function ($query, $value) {
                    $query->whereYear('created_at', $value);
                }),
                AllowedFilter::callback('month', function ($query, $value) {
                    $query->whereMonth('created_at', $value);
                }),
                'created_at'
            ])
            ->allowedSorts(['created_at'])
            ->orderByDesc('created_at')
            ->paginate($request->per_page);

        return ResponseHelper::success(new DealCollection($deals));

    }

    public function list(Request $request)
    {

        $deals = $deals = QueryBuilder::for(Deal::class)
        ->with(['course', 'classe', 'company', 'user', 'users'])
        // ->allowedFilters(['type', 'user_id','course_id','classe_id','company_id'])
        ->allowedFilters([
            AllowedFilter::exact('type'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('course_id'),
            AllowedFilter::exact('course_type'),
            AllowedFilter::exact('classe_id'),
            AllowedFilter::exact('company_id'),
            AllowedFilter::callback('year', function ($query, $value) {
                $query->whereYear('created_at', $value);
            }),
            AllowedFilter::callback('month', function ($query, $value) {
                $query->whereMonth('created_at', $value);
            }),
            'created_at'
        ])
            ->allowedSorts(['created_at'])
            ->orderByDesc('created_at')
            ->get();

        return ResponseHelper::success(DealResource::collection($deals));

    }
    public function update(DealRequest $request, Deal $deal)
    {
        $deal->update($request->validated());

        if ($request->type === 'company' && $request->has('trainees')) {
            $deal->users()->sync($request->trainees);
        }

        $deal->load(['course', 'classe', 'externalCourse' , 'company', 'user', 'users']);

        return ResponseHelper::success(new DealResource($deal));
    }


    public function destroy(Deal $deal)
    {
        $deal->delete();

        return ResponseHelper::success();
    }

    public function getDateOfDeal(Request $request)
    {
        $request->validate([
            'year' => ['required','integer'],
            'month' => ['required','integer','min:1','max:12'],
        ]);

        $year = $request->input('year');
        $month = $request->input('month');

        $dates = Deal::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->pluck('created_at')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->unique()
            ->values();

        return ResponseHelper::success($dates);

    }

    public function getDealByDate(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);

        $date = $request->input('date');
        $courseType = $request->input('course_type');

        $deals = Deal::whereDate('created_at', $date)
             ->when($courseType, function ($query, $courseType) {
                return $query->where('course_type', $courseType);
            })
            ->orderByDesc('created_at')
            ->get();

        return ResponseHelper::success(DealResource::collection($deals));
    }

    public function getCurrencies()
    {
        return ResponseHelper::success(CurrencyEnum::getValues());
    }

    public function getDealsByName(Request $request)
    {
        $name = $request->input('name');
        $type = $request->input('type');

        if (!$name || !in_array($type, ['company', 'individual'])) {
            return ResponseHelper::invalidData('Invalid name or type');
        }

        $deals = Deal::withCount('invoices')
            ->with(['company:id,name', 'user:id,name'])
            ->when($type === 'company', function ($query) use ($name) {
                $query->where('type', 'company')
                    ->whereHas('company', function ($q) use ($name) {
                        $q->where('name', $name);
                    });
            })
            ->when($type === 'individual', function ($query) use ($name) {
                $query->where('type', 'individual')
                    ->whereHas('user', function ($q) use ($name) {
                        $q->where('name', $name);
                    });
            })
            ->get();

         return ResponseHelper::success(DealResource::collection($deals));
    }

    public function getDealTrainees($dealId)
    {
        $deal = Deal::with(['company.users.roles', 'users.roles', 'user.roles'])->find($dealId);


        if (!$deal) {
            return ResponseHelper::DataNotFound('Deal not found');
        }

        $trainees = collect();

        if ($deal->company_id && $deal->company) {
            $trainees = $deal->company->users()
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'trainee');
                })
                ->get();
        }
        elseif ($deal->user_id && $deal->user) {
            $trainee = $deal->user;
            if ($trainee->roles->contains('name', 'trainee')) {
                $trainees = collect([$trainee]);
            }
        }
         elseif ($deal->users->isNotEmpty()) {
            $trainees = $deal->users->filter(function ($user) {
                return $user->roles->contains('name', 'trainee');
            })->values();
        }

        return ResponseHelper::success([
            'deal_id' => $deal->id,
            'trainees' => $trainees
        ]);
    }

}
