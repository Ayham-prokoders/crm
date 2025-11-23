<?php

namespace App\Http\Controllers;

use App\Services\FormsSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $syncService;

    public function __construct(FormsSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function handleNewForm(Request $request)
    {
        // Debug log
        Log::info('Webhook request received', [
            'all' => $request->all(),
            'headers' => $request->headers->all(),
            'secret_from_request' => $request->input('secret'),
            'secret_from_config' => config('services.webhook.secret'),
        ]);

        // التحقق من صحة الطلب
        $secret = $request->input('secret');
        if (trim($secret) !== trim(config('services.webhook.secret'))) {
            Log::warning('Invalid webhook secret attempt', [
                'got' => $secret,
                'expected' => config('services.webhook.secret'),
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $formData = $request->input('form');
        $siteKey = $request->input('site_key', 'L1');

        try {
            // معالجة الفورم الجديد
            $success = $this->syncService->processSingleForm($formData, $siteKey);

            if ($success) {
                Log::info("Webhook: New form processed", [
                    'id' => $formData['id'] ?? 'unknown',
                    'siteKey' => $siteKey,
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Form processed successfully'
                ]);
            } else {
                Log::error('Webhook: Failed to process form');
                return response()->json(['error' => 'Processing failed'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

}
