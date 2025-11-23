<?php

namespace App\Http\Controllers;

use App\Services\FormsSyncService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FormsController extends Controller
{
    protected $syncService;
    
    public function __construct(FormsSyncService $syncService)
    {
        $this->syncService = $syncService;
    }
    
    public function syncFormsRequests(Request $request)
    {
        $type = $request->get('type');
        $filters = $request->get('filters', []);
        $fromDate = $request->get('fromDate');
        $search = $request->get('search');
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $month = $request->get('month');
        
        $query = $this->syncService->getForms([
            'type' => $type,
            'fromDate' => $fromDate,
            'month' => $month,
            'search' => $search,
            'custom_filters' => $filters
        ]);
        
        // الحصول على الإحصائيات قبل الترحيل
        $statsQuery = clone $query;
        $typeStats = $statsQuery->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
        
        // الترحيل
        $total = $query->count();
        $forms = $query->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->map(function($form) {
                return [
                    'id' => $form->remote_id,
                    'type' => $form->type,
                    'original_type' => $form->original_type,
                    'created_at' => $form->created_at,
                    'data' => $form->data
                ];
            });
        
        return response()->json([
            'total' => $total,
            'current_page' => $page,
            'per_page' => $limit,
            'last_page' => ceil($total / $limit),
            'forms' => $forms,
            'stats' => $typeStats,
        ]);
    }
    
    public function triggerSync(Request $request)
    {
        $syncType = $request->get('type', 'incremental');
        $siteKey = $request->get('site', 'L1');
        
        try {
            if ($syncType === 'full') {
                $count = $this->syncService->fullSync($siteKey);
            } else {
                $count = $this->syncService->incrementalSync($siteKey);
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sync completed for $siteKey. Processed: $count forms"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }
}