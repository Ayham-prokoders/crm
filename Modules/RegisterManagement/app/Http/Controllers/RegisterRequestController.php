<?php

namespace Modules\RegisterManagement\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\SyncedFormsExport;
use Illuminate\Support\Facades\DB;
use App\Http\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Helper\RegisterationRequestHelper;
use Modules\RegisterManagement\Models\RegisterRequest;
use Modules\RegisterManagement\Http\Requests\ApproveRegisterRequest;
use Modules\RegisterManagement\Http\Services\ApproveRegisterationService;

class RegisterRequestController extends Controller
{
    protected $approveService;
    protected $syncHelper;

    public function __construct(ApproveRegisterationService $approveService,RegisterationRequestHelper $syncHelper)
    {
        $this->approveService = $approveService;
        $this->syncHelper = $syncHelper;
    }

    public function syncRegisterRequest(Request $request){
        try {
            $mailCount = $this->syncHelper->syncRegisterRequests();

            if ($mailCount > 0) {
                return ResponseHelper::success("Registration requests synchronized successfully. Total: $mailCount");
            } else {
                return ResponseHelper::success("No new registration requests found.");
            }
        } catch (\Exception $e) {

            return ResponseHelper::errorDetails(
                "Failed to sync registration requests.",
                ['error' => $e->getMessage()],
                500
            );
        }
    }
    public function approve(RegisterRequest $registration ,ApproveRegisterRequest $request)
    {

        try {
             if ($registration->status === 'approved') {
                return ResponseHelper::invalidData(['message' => 'Already processed']);
            }

            $messages = $this->approveService->approve($registration->toArray(), $request);
            if (!empty($messages)) {
                if (in_array('No class found for this registration.', $messages)) {
                    return ResponseHelper::operationFail('No class found for this registration.');
                }
                return ResponseHelper::invalidData([
                    'message' => 'Some participants are already registered.',
                    'conflicts' => $messages
                ]);
            }
            $registration->update(['status' => 'approved']);

            return ResponseHelper::success(data: ['message' => 'Registration approved successfully']);
        } catch (\Exception $e) {
            return ResponseHelper::operationFail(['message' => $e->getMessage()]);
        }

    }
    public function index(Request $request)
    {
        $query =  QueryBuilder::for(RegisterRequest::class)
            // ->where('status','waiting')
            ->allowedFilters([
            'company',
            AllowedFilter::exact('type'),
            'status',
            AllowedFilter::partial('course_name'),
            ])
            ->allowedSorts([ 'created_at'])
            ->orderByDesc('created_at');

            $reuests = $request->input('limit')
            ? $query->paginate($request->input('limit'))
            : $query->get();
        return ResponseHelper::success($reuests);
    }

    public function cancelRequest(RegisterRequest $registration ,Request $request){
        $registration->update([
            'status' => 'canceled',
            'cancellation_reason' => $request->input('cancellation_reason'),
        ]);
        return ResponseHelper::success();
    }


    public function getRegisterInfo(Request $request)
    {
        $type = $request->input('type');
        $courseId = $request->input('course_id');
        $course = null;

        if ($type === 'multiple-register-form') {
            $companyName = $request->input('company_name');
            if (!$companyName) {
            return ResponseHelper::invalidData('company name is required for multiple register ');
        }

        $course = RegisterRequest::where('course_id', $courseId)
                          ->where('company', $companyName)
                          ->first();

        } elseif ($type === 'register-form') {
            $fullName = $request->input('full_name') ?? null;
             if (!$fullName) {
            return ResponseHelper::invalidData('full name is required for register');
            }

            $course = RegisterRequest::where('course_id', $courseId)
                            ->where('full_name', $fullName)
                            ->first();
        } else {
            return ResponseHelper::invalidData('Invalid type');
        }

         if (!$course) {
            return ResponseHelper::DataNotFound('No matching registration found.');
        }

         $response = [
            'course_price' => $course->course_price,
            'payment_method' => $course->payment_method ?? null,
            'payment_mode' => $course->payment_mode ?? null,
        ];

        return ResponseHelper::success($response);
    }


    public function syncForms(Request $request)
    {
        $type = $request->input('type');
        $filters = $request->input('filters', []);
        $fromDate = $request->input('from_date');
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $result = $this->syncHelper->syncFormsRequests($type, $filters, $fromDate, $search, $limit, $page);

        $data = [
            'total' => $result['total'] ?? 0,
            'forms' => $result['forms'] ?? [],
            'pagination' => [
                'current_page' => $result['current_page'] ?? 1,
                'per_page' => $result['per_page'] ?? $limit,
                'last_page' => $result['last_page'] ?? 1,
            ],
        ];

        return ResponseHelper::success($data);
    }

    public function exportForms(Request $request)
    {
        $type = $request->input('type');
        $filters = $request->input('filters', []);
        $fromDate = $request->input('from_date');
        $search = $request->input('search');
        $result = $this->syncHelper->getAllFormsForExport($type, $filters, $fromDate, $search);
        $forms = $result['forms'] ?? [];

        if (empty($forms)) {
            return response()->json(['message' => 'No data found to export']);
        }

        \Log::info('Forms to export to Excel', $forms);
        return Excel::download(new SyncedFormsExport($forms), 'forms.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function getFormsStats(Request $request)
    {
        $fromDate = $request->input('from_date');
        $filters = $request->input('filters', []);

        $result = $this->syncHelper->syncFormsRequests(null, $filters, $fromDate, null, null, null);

        return ResponseHelper::success([
            'total' => $result['total'],
            'by_type' => $result['stats'],
        ]);
    }
      public function manageSync(Request $request)
    {
        $type = $request->input('type', 'incremental');
        $site = $request->input('site', 'L1');

        try {
            $count = $this->syncHelper->triggerAutoSync($site, $type);

            return ResponseHelper::success( 'تمت المزامنة بنجاح' );
        } catch (\Exception $e) {
            return ResponseHelper::operationFail('فشلت عملية المزامنة: ');
        }
    }

    // public function syncForms(Request $request)
    // {
    //     $type = $request->input('type');
    //     $filters = $request->input('filters', []);
    //     $fromDate = $request->input('from_date');
    //     $search = $request->input('search');
    //     $page = $request->input('page', 1);
    //     $limit = $request->input('limit', 10);
    //     $month = $request->input('month');

    //    $result = $this->syncHelper->syncFormsRequests($type, $filters, $fromDate, $search, $limit, $page , $month);

    //      $data = [
    //         'total' => $result['total'] ?? 0,
    //         'forms' => $result['forms'] ?? [],
    //         'pagination' => [
    //             'current_page' => $result['current_page'] ?? 1,
    //             'per_page' => $result['per_page'] ?? $limit,
    //             'last_page' => $result['last_page'] ?? 1,
    //         ],
    //     ];
    //     return ResponseHelper::success($data);
    // }

    // public function getFormsStats(Request $request)
    // {
    //     $fromDate = $request->input('from_date');
    //     $filters = $request->input('filters', []);
    //     $month = $request->input('month');

    //     $result = $this->syncHelper->syncFormsRequests(null, $filters, $fromDate, null, null, null, $month);

    //     return ResponseHelper::success([
    //         'total' => $result['total'],
    //         'by_type' => $result['stats'],
    //     ]);
    // }

    public function getRegisterRequestChart(Request $request)
    {
        $period = $request->input('period', 'monthly'); // daily , monthly
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m')); //  daily

        if ($period === 'daily') {
            $stats = RegisterRequest::select(
                    DB::raw('DAY(created_at) as label'),
                    DB::raw('SUM(CASE WHEN status="approved" THEN 1 ELSE 0 END) as approved'),
                    DB::raw('SUM(CASE WHEN status="canceled" THEN 1 ELSE 0 END) as canceled'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy(DB::raw('DAY(created_at)'))
                ->orderBy('label')
                ->get();
        } else { // monthly
            $stats = RegisterRequest::select(
                    DB::raw('MONTH(created_at) as label'),
                    DB::raw('SUM(CASE WHEN status="approved" THEN 1 ELSE 0 END) as approved'),
                    DB::raw('SUM(CASE WHEN status="canceled" THEN 1 ELSE 0 END) as canceled'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy('label')
                ->get();
        }

        $chartData = [
            'labels' => $stats->pluck('label'),
            'datasets' => [
                [
                    'label' => 'Approved',
                    'data' => $stats->pluck('approved'),
                    'backgroundColor' => '#4caf50'
                ],
                [
                    'label' => 'Canceled',
                    'data' => $stats->pluck('canceled'),
                    'backgroundColor' => '#f44336'
                ],
                [
                    'label' => 'Total',
                    'data' => $stats->pluck('total'),
                    'backgroundColor' => '#2196f3'
                ],
            ]
        ];

        return ResponseHelper::success($chartData);
    }

}
