<?php

namespace App\Services;

use App\Models\SyncedForm;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FormsSyncService
{
    protected $batchSize = 500;

    public function fullSync($siteKey = 'L1')
    {
        $lastSyncedId = SyncedForm::where('site_key', $siteKey)
            ->max('remote_id') ?? 0;

        $lastId = $lastSyncedId;
        $syncedCount = 0;

        do {
            $response = $this->fetchFormsBatch($lastId, $siteKey);

            if (!isset($response['table_data']) || empty($response['table_data'])) {
                break;
            }

            $batchCount = $this->processBatch($response['table_data'], $siteKey);
            $syncedCount += $batchCount;

            $lastId = $response['next_step'];

            usleep(200000); // تأخير 200ms

        } while ($lastId !== -1);

        return $syncedCount;
    }

    public function incrementalSync($siteKey = 'L1')
    {
        $lastSyncedAt = SyncedForm::where('site_key', $siteKey)
            ->max('synced_at') ?? Carbon::now()->subDay();

        return $this->syncSince($lastSyncedAt, $siteKey);
    }

    public function syncSince($timestamp, $siteKey = 'L1')
    {
        $lastId = 0;
        $syncedCount = 0;

        do {
            $response = $this->fetchFormsBatch($lastId, $siteKey);

            if (!isset($response['table_data']) || empty($response['table_data'])) {
                break;
            }

            $filteredData = array_filter($response['table_data'], function($form) use ($timestamp) {
                $formDate = isset($form['date']) ? Carbon::parse($form['date']) : null;
                return $formDate && $formDate->gt($timestamp);
            });

            if (!empty($filteredData)) {
                $batchCount = $this->processBatch($filteredData, $siteKey);
                $syncedCount += $batchCount;
            }

            $lastId = $response['next_step'];

        } while ($lastId !== -1);

        return $syncedCount;
    }

    protected function fetchFormsBatch($lastId, $siteKey)
    {
        try {
            $site = config("services.sites.$siteKey");
            $url = $site['url'] . '/api/data-integration/view';
            $secret = $site['shared_secret'];
            $timestamp = time();

            $data = [
                'table_name' => 'forms',
                'next_step' => $lastId,
                'batch_size' => $this->batchSize
            ];

            $signature = hash_hmac('sha256', $timestamp . json_encode($data), $secret);

            $response = Http::timeout(60)
                ->retry(3, 100)
                ->withHeaders([
                    'X-Timestamp' => $timestamp,
                    'X-Signature' => $signature,
                ])->post($url, $data);

            if ($response->failed()) {
                throw new \Exception('Failed to fetch forms from remote API. Status: ' . $response->status());
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to fetch forms batch: ' . $e->getMessage());
            return ['table_data' => [], 'next_step' => -1];
        }
    }

    protected function processBatch($formsData, $siteKey)
    {
        $processed = 0;

        foreach ($formsData as $form) {
            try {
                $allData = json_decode($form['all_data'] ?? '{}', true) ?? [];
                unset($allData['token']);

                SyncedForm::updateOrCreate(
                    [
                        'remote_id' => $form['id'],
                        'site_key' => $siteKey
                    ],
                    [
                        'type' => $this->normalizeType($form['type']),
                        'original_type' => $form['type'],
                        'form_created_at' => $form['date'] ?? now(),
                        'data' => $allData,
                        'synced_at' => now()
                    ]
                );

                $processed++;
            } catch (\Exception $e) {
                Log::error('Error processing form ' . ($form['id'] ?? 'unknown') . ': ' . $e->getMessage());
            }
        }

        return $processed;
    }

    protected function normalizeType($type)
    {
        return $type === 'multiple-register-form' ? 'register-form' : $type;
    }

    public function getForms($filters = [])
    {
        $query = SyncedForm::query();

        if (!empty($filters['type'])) {
            if ($filters['type'] === 'register-form') {
                $query->whereIn('type', ['register-form', 'multiple-register-form']);
            } else {
                $query->where('type', $filters['type']);
            }
        }

        if (!empty($filters['fromDate'])) {
            $query->where('form_created_at', '>=', Carbon::parse($filters['fromDate']));
        }

        if (!empty($filters['month'])) {
            [$year, $month] = explode('-', $filters['month']);
            $query->whereYear('form_created_at', $year)
                ->whereMonth('form_created_at', (int)$month);

        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('data->full_name', 'like', "%{$search}%")
                  ->orWhere('data->email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['custom_filters'])) {
            foreach ($filters['custom_filters'] as $key => $value) {
                if ($value === '' || $value === null) continue;
                if ($key === 'month') continue;
                $query->where("data->{$key}", $value);
            }
        }

        return $query;
    }

    public function processSingleForm($formData, $siteKey = 'L1')
    {
        try {
            if (isset($formData['all_data']) && is_string($formData['all_data'])) {
                $allData = json_decode($formData['all_data'], true) ?? [];
            } else {
                $allData = $formData;
            }

            $remoteId = $formData['id'] ?? ($allData['id'] ?? uniqid());
            $type = $formData['type'] ?? ($allData['type'] ?? 'unknown');

            // تحديد تاريخ الإنشاء
            if (isset($formData['date'])) {
                $formCreatedAt = $formData['date'];
            } elseif (isset($allData['date'])) {
                $formCreatedAt = $allData['date'];
            } elseif (isset($allData['created_at'])) {
                $formCreatedAt = $allData['created_at'];
            } else {
                $formCreatedAt = now();
            }

            SyncedForm::updateOrCreate(
                [
                    'remote_id' => $remoteId,
                    'site_key' => $siteKey
                ],
                [
                    'type' => $this->normalizeType($type),
                    'original_type' => $type,
                    'form_created_at' => $formCreatedAt,
                    'data' => $allData,
                    'synced_at' => now()
                ]
            );

            Log::info("Successfully processed form: " . $remoteId);
            return true;

        } catch (\Exception $e) {
            Log::error('Error processing single form: ' . $e->getMessage());
            Log::error('Form data that failed: ' . json_encode($formData));
            return false;
        }
    }
}
