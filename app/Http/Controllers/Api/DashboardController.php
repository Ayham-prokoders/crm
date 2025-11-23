<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Lms\Models\Classe;
use Modules\Lms\Models\Course;
use Modules\Lms\Models\Company;
use Modules\Lms\Models\Schedule;
use Illuminate\Support\Facades\DB;
use App\Http\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use Modules\DealManagement\Models\Deal;
use Modules\TrainerManagement\Models\Instructor;
use Modules\RegisterManagement\Models\RegisterRequest;

class DashboardController extends Controller
{
    public function getDashboardStatistics(Request $request)
    {
        $registrationStats = DB::table('register_requests')
            ->selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(case when status = 'approved' then 1 else 0 end) as approved")
            ->selectRaw("SUM(case when status = 'canceled' then 1 else 0 end) as canceled")
            ->first();

        return ResponseHelper::success([
            'deals'       => Deal::count(),
            'classes'     => Classe::count(),
            'courses'     => Course::count(),
            'companies'   => Company::count(),
            'trainees'    => User::query()->whereHas('roles', function ($query) {
                                $query->where('name', 'trainee');
                            })->count(),
            'instructors' => Instructor::count(),
            'totalRegistrations' => $registrationStats->total,
            'totalApprovedRegistrations' => $registrationStats->approved,
            'totalCanceledRegistrations' => $registrationStats->canceled,
        ]);
    }

    public function getCourseByStartDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
        ]);

        $schedules = Schedule::with('course')
        ->whereDate('start_date', $request->start_date)
        ->get();
        
        if ($schedules->isEmpty()) {
            return ResponseHelper::success('No courses found for the given start date.');
        }
        
        return ResponseHelper::success([
            'courses' => $schedules->pluck('course.name'),
        ]);
    }

}
